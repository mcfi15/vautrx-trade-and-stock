<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirdropClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'airdrop_id',
        'user_id',
        'claim_amount',
        'status',
        'admin_reason',
        'claimed_at',
    ];

    protected $casts = [
        'claim_amount' => 'decimal:18',
        'claimed_at' => 'datetime',
    ];

    public function airdrop()
    {
        return $this->belongsTo(Airdrop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
