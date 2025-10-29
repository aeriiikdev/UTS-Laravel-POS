<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class POSController extends Controller
{
    /**
     * Display POS page
     */
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();
        
        return view('pos.index', compact('products', 'categories'));
    }

    /**
     * Search product by code or name
     */
    public function searchProduct(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('code', 'LIKE', "%{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get single product by ID
     */
    public function getProduct($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        if (!$product->is_active || $product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Process transaction (checkout)
     */
    public function processTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,e-wallet',
            'notes' => 'nullable|string|max:500',
        ], [
            'items.required' => 'Keranjang belanja kosong',
            'items.min' => 'Minimal 1 produk harus ada di keranjang',
            'payment_amount.required' => 'Jumlah pembayaran harus diisi',
            'payment_amount.min' => 'Jumlah pembayaran tidak valid',
            'payment_method.required' => 'Metode pembayaran harus dipilih',
            'payment_method.in' => 'Metode pembayaran tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Calculate total amount from items
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        // Validate payment amount
        if ($request->payment_amount < $totalAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pembayaran kurang dari total belanja'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Generate unique transaction code
            $date = date('Ymd');
            $count = Transaction::whereDate('created_at', today())->count() + 1;
            $transactionCode = 'TRX-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            
            // Ensure unique code
            while (Transaction::where('transaction_code', $transactionCode)->exists()) {
                $count++;
                $transactionCode = 'TRX-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            // Calculate change
            $changeAmount = $request->payment_amount - $totalAmount;

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_code' => $transactionCode,
                'transaction_date' => now(),
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_amount' => $request->payment_amount,
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            // Create transaction details and update stock
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}");
                }

                // Create transaction detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'total_amount' => $transaction->total_amount,
                    'payment_amount' => $transaction->payment_amount,
                    'change_amount' => $changeAmount,
                    'receipt_url' => route('pos.receipt', $transaction->id)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print receipt - FIXED
     */
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['user', 'transactionDetails.product'])
            ->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }

    /**
     * Display transaction history
     */
    public function transactionHistory(Request $request)
    {
        $query = Transaction::with(['user', 'transactionDetails'])
            ->orderBy('transaction_date', 'desc');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by transaction code
        if ($request->has('search') && $request->search) {
            $query->where('transaction_code', 'LIKE', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(20);

        // Calculate summary
        $summary = [
            'total_transactions' => $transactions->total(),
            'total_amount' => Transaction::sum('total_amount'),
            'total_today' => Transaction::whereDate('transaction_date', today())->sum('total_amount'),
        ];

        return view('pos.history', compact('transactions', 'summary'));
    }
}