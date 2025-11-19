<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'title',
        'amount',
        'public_code',
        'secret_code',
        'message',
        'status',
        'redeemed_by',
        'redeemed_at',
        'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'redeemed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($giftCard) {
            if (empty($giftCard->public_code)) {
                $giftCard->public_code = 'GC-' . strtoupper(Str::random(12));
            }
            if (empty($giftCard->secret_code)) {
                $giftCard->secret_code = strtoupper(Str::random(16));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    public function redeemedBy()
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    public function transactions()
    {
        return $this->hasMany(GiftCardTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRedeemed($query)
    {
        return $query->where('status', 'redeemed');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isRedeemable()
    {
        return $this->status === 'active' && !$this->isExpired();
    }
}