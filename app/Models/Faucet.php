<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faucet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','cryptocurrency_id','amount','description','image',
        'start_at','end_at','cooldown_seconds','max_claims_per_user','is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'cooldown_seconds' => 'integer',
        'max_claims_per_user' => 'integer',
        'is_active' => 'boolean',
    ];

    public function coin()
    {
        return $this->belongsTo(Cryptocurrency::class,'cryptocurrency_id');
    }

    public function logs()
    {
        return $this->hasMany(FaucetLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', [1, '1', true, 'true']);
    }

    // public function scopeActive($q)
    // {
    //     $now = now();
    //     return $q->where('is_active', true)
    //              ->where(function($q2) use($now) {
    //                  $q2->whereNull('start_at')->orWhere('start_at','<=',$now);
    //              })
    //              ->where(function($q2) use($now) {
    //                  $q2->whereNull('end_at')->orWhere('end_at','>=',$now);
    //              });
    // }
}
