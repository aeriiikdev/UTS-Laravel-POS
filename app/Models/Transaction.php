<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_code',
        'transaction_date',
        'total_amount',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // FIXED: Changed from 'details' to 'transactionDetails'
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Helper method to generate transaction code
    public static function generateTransactionCode()
    {
        $date = date('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        $code = 'TRX-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        
        // Ensure unique
        while (self::where('transaction_code', $code)->exists()) {
            $count++;
            $code = 'TRX-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }

    // Accessor for formatted amounts
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format((float)$this->total_amount, 0, ',', '.');
    }

    public function getFormattedPaymentAttribute()
    {
        return 'Rp ' . number_format((float)$this->payment_amount, 0, ',', '.');
    }

    public function getFormattedChangeAttribute()
    {
        return 'Rp ' . number_format((float)$this->change_amount, 0, ',', '.');
    }
}