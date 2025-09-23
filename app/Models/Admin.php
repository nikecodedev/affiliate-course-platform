<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_enabled',
        'two_factor_secret',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Check if admin has verified 2FA in current session
     */
    public function hasVerified2FA()
    {
        return session('admin_2fa_verified', false);
    }

    /**
     * Generate 2FA secret
     */
    public function generateTwoFactorSecret()
    {
        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        return $google2fa->generateSecretKey();
    }

    /**
     * Get 2FA QR code URL
     */
    public function getTwoFactorQRCodeUrl()
    {
        $google2fa = new \PragmaRX\Google2FA\Google2FA();
        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $this->email,
            $this->two_factor_secret
        );
    }

    /**
     * Scope for active admins
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

