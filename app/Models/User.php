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
        'kyc_full_name',
        'kyc_document_type',
        'kyc_document_number',
        'kyc_front',
        'kyc_back',
        'kyc_selfie',
        'kyc_proof',
        'kyc_status',
        'kyc_rejection_reason',
        'fund_password',
        'fund_password_otp',
        'fund_password_otp_expires_at',
        'withdrawal_permission', // Add this line
        'withdrawal_limit', // Optional: if you want to set custom limits

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
        'fund_password_otp_expires_at' => 'datetime',
        'withdrawal_limit' => 'decimal:2', // Optional
    ];

    public function watchlists()
    {
        return $this->hasMany(\App\Models\Watchlist::class);
    }

    /**
     * Get the user's portfolios.
     */
    public function portfolios()
    {
        return $this->hasMany(\App\Models\Portfolio::class);
    }

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
    
    public function stocktransactions()
    {
        return $this->hasMany(StockTransaction::class);
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

     const WITHDRAWAL_ACTIVE = 'active';
    const WITHDRAWAL_SUSPENDED = 'suspended';
    const WITHDRAWAL_EXCEED_LIMIT = 'exceed_limit';

    /**
     * Get withdrawal permission status
     */
    public function getWithdrawalPermissionAttribute($value)
    {
        return $value ?? self::WITHDRAWAL_ACTIVE;
    }

    /**
     * Check if user can withdraw
     */
    public function canWithdraw()
    {
        return $this->withdrawal_permission === self::WITHDRAWAL_ACTIVE;
    }

    /**
     * Check if withdrawal is suspended
     */
    public function isWithdrawalSuspended()
    {
        return $this->withdrawal_permission === self::WITHDRAWAL_SUSPENDED;
    }

    /**
     * Check if withdrawal limit exceeded
     */
    public function hasExceededWithdrawalLimit()
    {
        return $this->withdrawal_permission === self::WITHDRAWAL_EXCEED_LIMIT;
    }

    /**
     * Update withdrawal permission
     */
    public function updateWithdrawalPermission($status)
    {
        $allowedStatuses = [self::WITHDRAWAL_ACTIVE, self::WITHDRAWAL_SUSPENDED, self::WITHDRAWAL_EXCEED_LIMIT];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new \InvalidArgumentException('Invalid withdrawal permission status');
        }

        $this->update(['withdrawal_permission' => $status]);
        
        return $this;
    }

    /**
     * Suspend withdrawals for user
     */
    public function suspendWithdrawals()
    {
        return $this->updateWithdrawalPermission(self::WITHDRAWAL_SUSPENDED);
    }

    /**
     * Activate withdrawals for user
     */
    public function activateWithdrawals()
    {
        return $this->updateWithdrawalPermission(self::WITHDRAWAL_ACTIVE);
    }

    /**
     * Mark as exceeded withdrawal limit
     */
    public function markExceededWithdrawalLimit()
    {
        return $this->updateWithdrawalPermission(self::WITHDRAWAL_EXCEED_LIMIT);
    }

    /**
     * Get withdrawal permission badge color
     */
    public function getWithdrawalPermissionBadgeAttribute()
    {
        switch ($this->withdrawal_permission) {
            case self::WITHDRAWAL_ACTIVE:
                return 'bg-green-100 text-green-800';
            case self::WITHDRAWAL_SUSPENDED:
                return 'bg-red-100 text-red-800';
            case self::WITHDRAWAL_EXCEED_LIMIT:
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get withdrawal permission label
     */
    public function getWithdrawalPermissionLabelAttribute()
    {
        switch ($this->withdrawal_permission) {
            case self::WITHDRAWAL_ACTIVE:
                return 'Active';
            case self::WITHDRAWAL_SUSPENDED:
                return 'Suspended';
            case self::WITHDRAWAL_EXCEED_LIMIT:
                return 'Exceed Limit';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get withdrawal permission icon
     */
    public function getWithdrawalPermissionIconAttribute()
    {
        switch ($this->withdrawal_permission) {
            case self::WITHDRAWAL_ACTIVE:
                return 'fas fa-check-circle';
            case self::WITHDRAWAL_SUSPENDED:
                return 'fas fa-ban';
            case self::WITHDRAWAL_EXCEED_LIMIT:
                return 'fas fa-exclamation-triangle';
            default:
                return 'fas fa-question-circle';
        }
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

    public function getWalletBySymbol($symbol)
    {
        $crypto = \App\Models\Cryptocurrency::where('symbol', $symbol)->first();
        if (!$crypto) return null;
        return $this->getWallet($crypto->id);
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
