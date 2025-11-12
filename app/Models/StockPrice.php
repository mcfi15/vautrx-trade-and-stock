<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'price',
        'volume',
        'timestamp',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'volume' => 'integer',
        'timestamp' => 'datetime',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}