<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaucetLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'faucet_id','user_id','amount','cryptocurrency_id','ip_address','user_agent','status','reason','claimed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'claimed_at' => 'datetime',
    ];

    public function faucet()
    {
        return $this->belongsTo(Faucet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
