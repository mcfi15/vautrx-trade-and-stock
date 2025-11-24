<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalAddress extends Model
{
    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'network',
        'address',
        'dest_tag',
        'label',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(\App\Models\Cryptocurrency::class);
    }
}
