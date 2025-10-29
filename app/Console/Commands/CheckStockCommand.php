<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Transaction;


class CheckStockCommand extends Command
{
    protected $signature = 'pos:check-stock {threshold=10}';
    protected $description = 'Check products with low stock';

    public function handle()
    {
        $threshold = $this->argument('threshold');
        
        $lowStock = Product::where('stock', '<', $threshold)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();
        
        $outOfStock = Product::where('stock', 0)->with('category')->get();
        
        $this->warn("⚠️  Produk Stok Menipis (< {$threshold}):");
        $this->newLine();
        
        if ($lowStock->isEmpty()) {
            $this->info("✅ Tidak ada produk dengan stok menipis");
        } else {
            $this->table(
                ['Kode', 'Nama Produk', 'Kategori', 'Stok'],
                $lowStock->map(function ($product) {
                    return [
                        $product->code,
                        $product->name,
                        $product->category->name,
                        $product->stock
                    ];
                })
            );
        }
        
        $this->newLine();
        $this->error("❌ Produk Stok Habis:");
        $this->newLine();
        
        if ($outOfStock->isEmpty()) {
            $this->info("✅ Tidak ada produk yang stok habis");
        } else {
            $this->table(
                ['Kode', 'Nama Produk', 'Kategori'],
                $outOfStock->map(function ($product) {
                    return [
                        $product->code,
                        $product->name,
                        $product->category->name
                    ];
                })
            );
        }
        
        return 0;
    }
}