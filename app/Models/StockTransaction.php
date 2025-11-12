<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stock_id',
        'type',
        'quantity',
        'price',
        'total_amount',
        'commission',
        'net_amount',
        'status',
        'executed_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'executed_at' => 'datetime',
    ];

    const TYPE_BUY = 'buy';
    const TYPE_SELL = 'sell';

    const STATUS_PENDING = 'pending';
    const STATUS_EXECUTED = 'executed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function scopeBuy($query)
    {
        return $query->where('type', self::TYPE_BUY);
    }

    public function scopeSell($query)
    {
        return $query->where('type', self::TYPE_SELL);
    }

    public function scopeExecuted($query)
    {
        return $query->where('status', self::STATUS_EXECUTED);
    }
}
