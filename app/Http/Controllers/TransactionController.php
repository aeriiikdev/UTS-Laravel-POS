<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display POS page
     */
    public function index()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();
        
        return view('transactions.index', compact('products'));
    }

    /**
     * Store a new transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,e-wallet',
            'notes' => 'nullable|string',
        ], [
            'cart.required' => 'Keranjang belanja kosong',
            'cart.min' => 'Minimal 1 produk harus ada di keranjang',
            'payment_amount.required' => 'Jumlah pembayaran harus diisi',
            'payment_amount.min' => 'Jumlah pembayaran tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Validate payment amount
        if ($request->payment_amount < $request->total_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pembayaran kurang dari total belanja'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Generate transaction code
            $transactionCode = 'INV-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Calculate change
            $changeAmount = $request->payment_amount - $request->total_amount;

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'transaction_code' => $transactionCode,
                'transaction_date' => now(),
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'payment_amount' => $request->payment_amount,
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            // Create transaction details and update stock
            foreach ($request->cart as $item) {
                $product = Product::find($item['product_id']);

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
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'change_amount' => $changeAmount,
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
     * Display transaction history
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])
            ->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Display all transactions
     */
    public function history(Request $request)
    {
        $query = Transaction::with(['user', 'details'])
            ->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->paginate(20);

        return view('transactions.history', compact('transactions'));
    }

    /**
     * Print receipt
     */
    public function print($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])
            ->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }

    /**
     * Cancel transaction
     */
    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::with('details.product')->findOrFail($id);

            // Check if transaction can be cancelled
            if ($transaction->status == 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi sudah dibatalkan'
                ], 422);
            }

            // Return stock
            foreach ($transaction->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }

            // Update transaction status
            $transaction->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}