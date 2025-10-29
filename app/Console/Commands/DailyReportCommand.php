<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Transaction;

/**
 * Command untuk melihat laporan penjualan harian
 * Jalankan: php artisan pos:daily-report
 */
class DailyReportCommand extends Command
{
    protected $signature = 'pos:daily-report {date?}';
    protected $description = 'Generate daily sales report';

    public function handle()
    {
        $date = $this->argument('date') ?? today()->toDateString();
        
        $transactions = Transaction::whereDate('created_at', $date)->get();
        
        $this->info("📊 Laporan Penjualan: " . $date);
        $this->info("=====================================");
        $this->line("Total Transaksi: " . $transactions->count());
        $this->line("Total Penjualan: Rp " . number_format($transactions->sum('total'), 0, ',', '.'));
        $this->line("Total Diskon: Rp " . number_format($transactions->sum('discount'), 0, ',', '.'));
        $this->line("Total Pajak: Rp " . number_format($transactions->sum('tax'), 0, ',', '.'));
        
        $this->newLine();
        $this->info("Metode Pembayaran:");
        
        foreach (['cash' => 'Tunai', 'card' => 'Kartu', 'e-wallet' => 'E-Wallet'] as $key => $label) {
            $count = $transactions->where('payment_method', $key)->count();
            $total = $transactions->where('payment_method', $key)->sum('total');
            if ($count > 0) {
                $this->line("  {$label}: {$count} transaksi (Rp " . number_format($total, 0, ',', '.') . ")");
            }
        }
        
        return 0;
    }
}