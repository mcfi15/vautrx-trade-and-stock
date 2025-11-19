<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMiningMachine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mining_pool_id',
        'quantity',
        'total_cost',
        'daily_reward',
        'start_date',
        'end_date',
        'is_active',
        'status'
    ];

    protected $casts = [
        'total_cost' => 'decimal:8',
        'daily_reward' => 'decimal:8',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function miningPool()
    {
        return $this->belongsTo(MiningPool::class);
    }

    public function rewards()
    {
        return $this->hasMany(MiningReward::class);
    }

    public function getRemainingDaysAttribute()
    {
        return now()->diffInDays($this->end_date, false);
    }

    public function getTotalEarnedAttribute()
    {
        return $this->rewards()->sum('amount');
    }
}