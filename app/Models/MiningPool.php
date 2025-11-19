<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'price',
        'total',
        'available',
        'daily_reward',
        'duration_days',
        'user_limit',
        'power',
        'logo_url',
        'description',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'daily_reward' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function userMachines()
    {
        return $this->hasMany(UserMiningMachine::class);
    }

    public function rewards()
    {
        return $this->hasMany(MiningReward::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTotalRewardAttribute()
    {
        return $this->daily_reward * $this->duration_days;
    }
}