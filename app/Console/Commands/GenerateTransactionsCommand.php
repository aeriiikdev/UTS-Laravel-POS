<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Transaction;


class GenerateTransactionsCommand extends Command
{
    protected $signature = 'pos:generate-transactions {count=10}';
    protected $description = 'Generate dummy transactions for testing';

    public function handle()
    {
        $count = $this->argument('count');
        $bar = $this->output->createProgressBar($count);
        
        $this->info("Generating {$count} dummy transactions...");
        $bar->start();
        
        $users = \App\Models\User::all();
        $products = Product::where('stock', '>', 0)->get();
        
        if ($products->isEmpty()) {
            $this->error("No products available!");
            return 1;
        }
        
        for ($i = 0; $i < $count; $i++) {
            $user = $users->random();
            $numItems = rand(1, 5);
            $selectedProducts = $products->random(min($numItems, $products->count()));
            
            $subtotal = 0;
            $items = [];
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
                $subtotal += $product->price * $quantity;
            }
            
            $discount = rand(0, 1) ? rand(0, $subtotal * 0.1) : 0;
            $tax = ($subtotal - $discount) * 0.10;
            $total = $subtotal - $discount + $tax;
            $paid = $total + rand(0, 50000);
            
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'paid' => $paid,
                'change' => $paid - $total,
                'payment_method' => ['cash', 'card', 'e-wallet'][rand(0, 2)],
                'notes' => 'Dummy transaction for testing'
            ]);
            
            foreach ($items as $item) {
                \App\Models\TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
                
                // Update stock
                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Successfully generated {$count} transactions!");
        
        return 0;
    }
}