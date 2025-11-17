<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StakePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cryptocurrency_id',
        'percent',
        'durations',
        'min_amount',
        'is_active',
    ];

    protected $casts = [
        'durations' => 'array',
        'percent' => 'decimal:2',
        'min_amount' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    public function userStakes()
    {
        return $this->hasMany(UserStake::class);
    }
}
