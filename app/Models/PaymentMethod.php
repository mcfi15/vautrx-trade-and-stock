<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'cryptocurrency_id',
        'name',
        'address',
        'type',
        'bank_name',
        'account_name',
        'account_number',
        'swift_code'
    ];

    public function cryptocurrency()
    {
        return $this->belongsTo(Cryptocurrency::class);
    }
}

