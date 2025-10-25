<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_number',
        'order_id',
        'trading_pair_id',
        'buyer_id',
        'seller_id',
        'price',
        'quantity',
        'total_amount',
        'buyer_fee',
        'seller_fee',
        'blockchain_tx_hash',
        'blockchain_status',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'quantity' => 'decimal:18',
        'total_amount' => 'decimal:8',
        'buyer_fee' => 'decimal:8',
        'seller_fee' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trade) {
            if (empty($trade->trade_number)) {
                $trade->trade_number = 'TRD-' . strtoupper(Str::random(12));
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tradingPair()
    {
        return $this->belongsTo(TradingPair::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
