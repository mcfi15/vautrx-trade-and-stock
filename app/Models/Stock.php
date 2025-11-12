<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'current_price',
        'opening_price',
        'closing_price',
        'high_price',
        'low_price',
        'volume',
        'market_cap',
        'sector',
        'exchange',
        'is_active',
        'last_updated',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'opening_price' => 'decimal:2',
        'closing_price' => 'decimal:2',
        'high_price' => 'decimal:2',
        'low_price' => 'decimal:2',
        'volume' => 'integer',
        'market_cap' => 'decimal:2',
        'is_active' => 'boolean',
        'last_updated' => 'datetime',
    ];

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function stocktransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function stockPrices()
    {
        return $this->hasMany(StockPrice::class);
    }

    public function priceHistory()
    {
        return $this->hasMany(StockPriceHistory::class);
    }

    public function getChangeAttribute()
    {
        if ($this->closing_price && $this->current_price) {
            return $this->current_price - $this->closing_price;
        }
        return 0;
    }

    public function getChangePercentageAttribute()
    {
        if ($this->closing_price && $this->current_price && $this->closing_price > 0) {
            return (($this->current_price - $this->closing_price) / $this->closing_price) * 100;
        }
        return 0;
    }

    /**
     * Alias for current_price for view compatibility
     */
    public function getPriceAttribute()
    {
        return $this->current_price;
    }
}