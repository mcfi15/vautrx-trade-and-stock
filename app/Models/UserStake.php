<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStake extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stake_plan_id',
        'cryptocurrency_id',
        'amount',
        'duration',
        'yield_percent',
        'status',
        'started_at',
        'ends_at',
        'completed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:18',
        'yield_percent' => 'decimal:4',
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(StakePlan::class, 'stake_plan_id');
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }
}
