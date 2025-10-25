<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'transaction_hash',
        'amount',
        'fee',
        'status',
        'confirmations',
        'required_confirmations',
    ];

    protected $casts = [
        'amount' => 'decimal:18',
        'fee' => 'decimal:18',
        'confirmations' => 'integer',
        'required_confirmations' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    public function getIsConfirmedAttribute()
    {
        return $this->confirmations >= $this->required_confirmations;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 8);
    }

    public function getFormattedFeeAttribute()
    {
        return number_format($this->fee, 8);
    }
}
