<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cryptocurrency_id',
        'address',
        'balance',
        'locked_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:18',
        'locked_balance' => 'decimal:18',
    ];

    protected $appends = ['available_balance']; // ✅ Add this line

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    public function getAvailableBalanceAttribute()
    {
        return $this->balance - $this->locked_balance;
    }

    public function lockBalance($amount)
    {
        if ($this->availableBalance >= $amount) {
            $this->locked_balance += $amount;
            return $this->save();
        }
        return false;
    }

    public function unlockBalance($amount)
    {
        $this->locked_balance -= $amount;
        if ($this->locked_balance < 0) {
            $this->locked_balance = 0;
        }
        return $this->save();
    }

    public function addBalance($amount)
    {
        $this->balance += $amount;
        return $this->save();
    }

    public function subtractBalance($amount)
    {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            return $this->save();
        }
        return false;
    }
}
