<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPriceHistory extends Model
{
    use HasFactory;

    protected $table = 'stock_price_history';

    protected $fillable = [
        'stock_id',
        'date',
        'open_price',
        'high_price',
        'low_price',
        'close_price',
        'volume',
        'adjusted_close'
    ];

    protected $casts = [
        'date' => 'date',
        'open_price' => 'decimal:2',
        'high_price' => 'decimal:2',
        'low_price' => 'decimal:2',
        'close_price' => 'decimal:2',
        'adjusted_close' => 'decimal:2',
        'volume' => 'integer'
    ];

    /**
     * Get the stock that owns the price history
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
