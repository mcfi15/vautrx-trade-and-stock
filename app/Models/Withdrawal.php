<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'withdrawal_address',
        'amount',
        'fee',
        'net_amount',
        'status',
        'transaction_id',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:18',
        'fee' => 'decimal:18',
        'net_amount' => 'decimal:18',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 8);
    }

    public function getFormattedFeeAttribute()
    {
        return number_format($this->fee, 8);
    }

    public function getFormattedNetAmountAttribute()
    {
        return number_format($this->net_amount, 8);
    }
}
