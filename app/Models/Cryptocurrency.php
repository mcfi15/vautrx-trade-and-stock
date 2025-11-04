<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cryptocurrency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'symbol',
        'name',
        'coingecko_id',
        'binance_symbol',
        'contract_address',
        'blockchain',
        'decimals',
        'logo_url',
        'current_price',
        'high_24h',
        'low_24h',
        'price_change_24h',
        'market_cap',
        'volume_24h',
        'is_active',
        'is_tradable',
        'enable_realtime',
        'price_updated_at',
    ];

    protected $casts = [
        'current_price' => 'decimal:8',
        'high_24h' => 'decimal:8',
        'low_24h' => 'decimal:8',
        'price_change_24h' => 'decimal:2',
        'market_cap' => 'decimal:2',
        'volume_24h' => 'decimal:2',
        'is_active' => 'boolean',
        'is_tradable' => 'boolean',
        'enable_realtime' => 'boolean',
        'price_updated_at' => 'datetime',
    ];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function basePairs()
    {
        return $this->hasMany(TradingPair::class, 'base_currency_id');
    }

    public function quotePairs()
    {
        return $this->hasMany(TradingPair::class, 'quote_currency_id');
    }


    // public function baseTradingPairs()
    // {
    //     return $this->hasMany(TradingPair::class, 'base_currency_id');
    // }

    // public function quoteTradingPairs()
    // {
    //     return $this->hasMany(TradingPair::class, 'quote_currency_id');
    // }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    // public function tradingPairsBase()
    // {
    //     return $this->hasMany(TradingPair::class, 'base_currency_id');
    // }

    // public function tradingPairsQuote()
    // {
    //     return $this->hasMany(TradingPair::class, 'quote_currency_id');
    // }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTradable($query)
    {
        return $query->where('is_tradable', true);
    }
}
