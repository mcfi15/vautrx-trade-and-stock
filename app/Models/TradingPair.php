<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_currency_id',
        'quote_currency_id',
        'symbol',
        'min_trade_amount',
        'max_trade_amount',
        'price_precision',
        'quantity_precision',
        'trading_fee',
        'is_active',
        'last_price',
        'price_change_percent',
        'high_24h',
        'low_24h',
        'volume_24h',
    ];

    protected $casts = [
        'min_trade_amount' => 'decimal:8',
        'max_trade_amount' => 'decimal:8',
        'price_precision' => 'decimal:8',
        'quantity_precision' => 'decimal:8',
        'trading_fee' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function baseCurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'base_currency_id');
    }

    public function quoteCurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'quote_currency_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getCurrentPrice()
    {
        $basePrice = $this->baseCurrency->current_price;
        $quotePrice = $this->quoteCurrency->current_price;
        
        if ($quotePrice > 0) {
            return $basePrice / $quotePrice;
        }
        
        return 0;
    }
}
