<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'browser',
        'device',
        'platform',
        'location',
        'latitude',
        'longitude',
        'success',
        'failure_reason',
        'country',
        'city',
        'isp',
        'login_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'success' => 'boolean',
        'login_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a formatted location string
     */
    public function getFormattedLocationAttribute()
    {
        if ($this->city && $this->country) {
            return "{$this->city}, {$this->country}";
        } elseif ($this->country) {
            return $this->country;
        } elseif ($this->location) {
            return $this->location;
        }
        
        return 'Unknown';
    }

    /**
     * Get device info string
     */
    public function getDeviceInfoAttribute()
    {
        $info = [];
        
        if ($this->device) {
            $info[] = $this->device;
        }
        
        if ($this->browser) {
            $info[] = $this->browser;
        }
        
        if ($this->platform) {
            $info[] = $this->platform;
        }
        
        return implode(' - ', $info);
    }
}