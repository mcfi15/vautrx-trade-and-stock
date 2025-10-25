<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'trading_pair_id',
        'type',
        'side',
        'price',
        'stop_price',
        'quantity',
        'filled_quantity',
        'remaining_quantity',
        'total_amount',
        'fee',
        'status',
        'executed_at',
        'cancelled_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'stop_price' => 'decimal:8',
        'quantity' => 'decimal:18',
        'filled_quantity' => 'decimal:18',
        'remaining_quantity' => 'decimal:18',
        'total_amount' => 'decimal:8',
        'fee' => 'decimal:8',
        'executed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(12));
            }
            if (empty($order->remaining_quantity)) {
                $order->remaining_quantity = $order->quantity;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tradingPair()
    {
        return $this->belongsTo(TradingPair::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBuy($query)
    {
        return $query->where('side', 'buy');
    }

    public function scopeSell($query)
    {
        return $query->where('side', 'sell');
    }

    public function isFilled()
    {
        return $this->filled_quantity >= $this->quantity;
    }

    public function isPartiallyFilled()
    {
        return $this->filled_quantity > 0 && $this->filled_quantity < $this->quantity;
    }

    public function cancel()
    {
        $this->status = 'cancelled';
        $this->cancelled_at = now();
        return $this->save();
    }
}
