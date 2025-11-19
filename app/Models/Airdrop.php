<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airdrop extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'holding_currency_id',
        'min_hold_amount',
        'airdrop_currency_id',
        'airdrop_amount',
        'description',
        'image',
        'start_at',
        'end_at',
        'is_active',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'airdrop_amount' => 'decimal:8',
        'min_hold_amount' => 'decimal:8',
    ];

    public function holdingCurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'holding_currency_id');
    }

    public function airdropCurrency()
    {
        return $this->belongsTo(Cryptocurrency::class, 'airdrop_currency_id');
    }

    public function claims()
    {
        return $this->hasMany(AirdropClaim::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function($q){
                         $q->whereNull('start_at')->orWhere('start_at','<=',now());
                      })
                      ->where(function($q){
                         $q->whereNull('end_at')->orWhere('end_at','>=',now());
                      });
    }
}
