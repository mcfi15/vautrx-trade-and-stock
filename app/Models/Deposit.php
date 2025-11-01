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
        'transaction_id',
        'transaction_hash',
        'payment_proof_path',
        'payment_proof_filename',
        'amount',
        'fee',
        'status',
        'confirmations',
        'required_confirmations',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
        'reviewed_by_admin',
    ];

    protected $casts = [
        'amount' => 'decimal:18',
        'fee' => 'decimal:18',
        'confirmations' => 'integer',
        'required_confirmations' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'cryptocurrency_id', 'cryptocurrency_id')
                    ->where('user_id', $this->user_id);
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

    /**
     * Relationship with admin who reviewed this deposit
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Relationship with admin who reviewed this deposit (if exists in admins table)
     */
    public function reviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin');
    }

    /**
     * Check if payment proof exists
     */
    public function hasPaymentProof()
    {
        return !empty($this->payment_proof_path);
    }

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof($file)
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment-proofs', $fileName, 'public');
        
        // Delete old payment proof if exists
        if ($this->payment_proof_path) {
            \Storage::disk('public')->delete($this->payment_proof_path);
        }
        
        $this->update([
            'payment_proof_path' => $path,
            'payment_proof_filename' => $file->getClientOriginalName()
        ]);
        
        return $path;
    }

    /**
     * Delete payment proof
     */
    public function deletePaymentProof()
    {
        if ($this->payment_proof_path) {
            \Storage::disk('public')->delete($this->payment_proof_path);
            $this->update([
                'payment_proof_path' => null,
                'payment_proof_filename' => null
            ]);
        }
    }

    /**
     * Scope for deposits with payment proof
     */
    public function scopeWithPaymentProof($query)
    {
        return $query->whereNotNull('payment_proof_path');
    }

    /**
     * Scope for deposits without payment proof
     */
    public function scopeWithoutPaymentProof($query)
    {
        return $query->whereNull('payment_proof_path');
    }

    // protected $fillable = [
    //     'user_id',
    //     'cryptocurrency_id',
    //     'transaction_hash',
    //     'payment_proof_path',
    //     'payment_proof_filename',
    //     'amount',
    //     'fee',
    //     'status',
    //     'confirmations',
    //     'required_confirmations',
    //     'admin_notes',
    //     'reviewed_at',
    //     'reviewed_by',
    // ];

    // protected $casts = [
    //     'amount' => 'decimal:18',
    //     'fee' => 'decimal:18',
    //     'confirmations' => 'integer',
    //     'required_confirmations' => 'integer',
    //     'reviewed_at' => 'datetime',
    // ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function cryptocurrency()
    // {
    //     return $this->belongsTo(Cryptocurrency::class);
    // }

    // public function getIsConfirmedAttribute()
    // {
    //     return $this->confirmations >= $this->required_confirmations;
    // }

    // public function getFormattedAmountAttribute()
    // {
    //     return number_format($this->amount, 8);
    // }

    // public function getFormattedFeeAttribute()
    // {
    //     return number_format($this->fee, 8);
    // }

    // /**
    //  * Relationship with admin who reviewed this deposit
    //  */
    // public function reviewedBy()
    // {
    //     return $this->belongsTo(User::class, 'reviewed_by');
    // }

    // /**
    //  * Get payment proof URL
    //  */
    // public function getPaymentProofUrlAttribute()
    // {
    //     if ($this->payment_proof_path) {
    //         return asset('storage/' . $this->payment_proof_path);
    //     }
    //     return null;
    // }

    // /**
    //  * Check if payment proof exists
    //  */
    // public function hasPaymentProof()
    // {
    //     return !empty($this->payment_proof_path);
    // }

    // /**
    //  * Upload payment proof
    //  */
    // public function uploadPaymentProof($file)
    // {
    //     $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //     $path = $file->storeAs('payment-proofs', $fileName, 'public');
        
    //     // Delete old payment proof if exists
    //     if ($this->payment_proof_path) {
    //         \Storage::disk('public')->delete($this->payment_proof_path);
    //     }
        
    //     $this->update([
    //         'payment_proof_path' => $path,
    //         'payment_proof_filename' => $file->getClientOriginalName()
    //     ]);
        
    //     return $path;
    // }

    // /**
    //  * Delete payment proof
    //  */
    // public function deletePaymentProof()
    // {
    //     if ($this->payment_proof_path) {
    //         \Storage::disk('public')->delete($this->payment_proof_path);
    //         $this->update([
    //             'payment_proof_path' => null,
    //             'payment_proof_filename' => null
    //         ]);
    //     }
    // }

    // /**
    //  * Scope for deposits with payment proof
    //  */
    // public function scopeWithPaymentProof($query)
    // {
    //     return $query->whereNotNull('payment_proof_path');
    // }

    // /**
    //  * Scope for deposits without payment proof
    //  */
    // public function scopeWithoutPaymentProof($query)
    // {
    //     return $query->whereNull('payment_proof_path');
    // }
}
