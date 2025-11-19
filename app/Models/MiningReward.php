<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mining_pool_id',
        'user_mining_machine_id',
        'amount',
        'reward_date',
        'is_paid',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'reward_date' => 'date',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function miningPool()
    {
        return $this->belongsTo(MiningPool::class);
    }

    public function userMiningMachine()
    {
        return $this->belongsTo(UserMiningMachine::class);
    }
}