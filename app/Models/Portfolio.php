<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stock_id',
        'quantity',
        'average_price',
        'total_invested',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'average_price' => 'decimal:2',
        'total_invested' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function getCurrentValueAttribute()
    {
        return $this->quantity * $this->stock->current_price;
    }

    public function getProfitLossAttribute()
    {
        return $this->current_value - $this->total_invested;
    }

    public function getProfitLossPercentageAttribute()
    {
        if ($this->total_invested > 0) {
            return (($this->current_value - $this->total_invested) / $this->total_invested) * 100;
        }
        return 0;
    }
}