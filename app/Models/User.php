<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'auth_provider',
        'is_admin',
        'is_active',
        'kyc_verified',
        'two_factor_enabled',
        'two_factor_secret',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'email_verification_token',
        'email_verification_token_expires_at',
        'email_verified_for_login_at',
        'email_notifications',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verified_for_login_at' => 'datetime',
        'email_verification_token_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'kyc_verified' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'email_notifications' => 'boolean',
    ];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function buyTrades()
    {
        return $this->hasMany(Trade::class, 'buyer_id');
    }

    public function sellTrades()
    {
        return $this->hasMany(Trade::class, 'seller_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class)->latest();
    }

    public function getWallet($cryptocurrencyId)
    {
        return $this->wallets()->where('cryptocurrency_id', $cryptocurrencyId)->first();
    }

    public function getOrCreateWallet($cryptocurrencyId)
    {
        try {
            // Validate input
            if (!$cryptocurrencyId) {
                throw new \InvalidArgumentException('Cryptocurrency ID is required');
            }
            
            // Check if user exists and is valid
            if (!$this->exists) {
                throw new \Exception('User does not exist');
            }
            
            $wallet = $this->getWallet($cryptocurrencyId);
            
            if (!$wallet) {
                // Verify cryptocurrency exists
                $cryptocurrency = \App\Models\Cryptocurrency::find($cryptocurrencyId);
                if (!$cryptocurrency) {
                    throw new \Exception('Cryptocurrency not found: ' . $cryptocurrencyId);
                }
                
                $wallet = $this->wallets()->create([
                    'cryptocurrency_id' => $cryptocurrencyId,
                    'balance' => 0,
                    'locked_balance' => 0,
                ]);
            }
            
            return $wallet;
            
        } catch (\Exception $e) {
            // Log the error but don't break the trading interface
            \Log::warning('Wallet creation/retrieval failed: ' . $e->getMessage(), [
                'user_id' => $this->id,
                'cryptocurrency_id' => $cryptocurrencyId,
                'user_email' => $this->email
            ]);
            
            // Return a dummy wallet with zero balance to prevent interface breakage
            return new \App\Models\Wallet([
                'user_id' => $this->id,
                'cryptocurrency_id' => $cryptocurrencyId,
                'balance' => 0,
                'locked_balance' => 0,
            ]);
        }
    }

    public function getTotalPortfolioValue()
    {
        $total = 0;
        foreach ($this->wallets as $wallet) {
            $crypto = $wallet->cryptocurrency;
            $total += $wallet->balance * $crypto->current_price;
        }
        return $total;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Boot the model and register event listeners
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->createDefaultWallets();
        });
    }

    /**
     * Create default wallets for all active cryptocurrencies
     */
    public function createDefaultWallets()
    {
        $cryptocurrencies = Cryptocurrency::active()->get();
        
        foreach ($cryptocurrencies as $crypto) {
            $this->wallets()->firstOrCreate([
                'cryptocurrency_id' => $crypto->id,
            ], [
                'balance' => 0,
                'locked_balance' => 0,
            ]);
        }
    }

    /**
     * Get total portfolio value in USDT
     */
    public function getTotalPortfolioValueUsdt()
    {
        $total = 0;
        foreach ($this->wallets as $wallet) {
            $crypto = $wallet->cryptocurrency;
            if ($crypto && $wallet->balance > 0) {
                $total += $wallet->balance * $crypto->current_price;
            }
        }
        return $total;
    }
}
