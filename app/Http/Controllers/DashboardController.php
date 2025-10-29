<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pendapatan Hari Ini
        $todayRevenue = Transaction::whereDate('transaction_date', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // Total Pendapatan Bulan Ini
        $monthRevenue = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Total Transaksi Hari Ini
        $todayTransactions = Transaction::whereDate('transaction_date', today())
            ->where('status', 'completed')
            ->count();

        // Total Produk
        $totalProducts = Product::where('is_active', true)->count();

        // Produk Stok Rendah (stok <= 10)
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Produk Terlaris Bulan Ini
        $topProducts = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->whereMonth('transactions.transaction_date', now()->month)
            ->whereYear('transactions.transaction_date', now()->year)
            ->where('transactions.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.image',
                DB::raw('SUM(transaction_details.quantity) as total_sold'),
                DB::raw('SUM(transaction_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Transaksi Terakhir
        $recentTransactions = Transaction::with('user')
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // Data Grafik Pendapatan 7 Hari Terakhir
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Transaction::whereDate('transaction_date', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
            
            $revenueChart[] = [
                'date' => $date->format('d M'),
                'revenue' => $revenue
            ];
        }

        // Total Kategori
        $totalCategories = Category::where('is_active', true)->count();

        return view('dashboard.index', compact(
            'todayRevenue',
            'monthRevenue',
            'todayTransactions',
            'totalProducts',
            'lowStockProducts',
            'topProducts',
            'recentTransactions',
            'revenueChart',
            'totalCategories'
        ));
    }
}