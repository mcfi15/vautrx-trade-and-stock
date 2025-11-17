<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stake extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'amount',
        'duration',
        'percent',
        'minimum_amount',
        'status',
        'ends_at',
    ];

    protected $casts = [
        'amount' => 'decimal:18',
        'minimum_amount' => 'decimal:18',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }
}
