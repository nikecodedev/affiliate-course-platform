<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'birth_date',
        'gender',
        'tracking_tags',
        'facebook_pixel_id',
        'google_tag_manager_id',
        'google_analytics_id',
        'custom_tracking_code',
        'is_active',
        'active_network',
        'activated_at',
        'email_verified_at',
        'last_login_at',
        'login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'tracking_tags' => 'array',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the client's invoices
     */
    public function invoices()
    {
        return $this->hasMany(ClientInvoice::class);
    }

    /**
     * Get the client's leads
     */
    public function leads()
    {
        return $this->hasMany(ClientLead::class);
    }

    /**
     * Get the client's transactions
     */
    public function transactions()
    {
        return $this->hasMany(ClientTransaction::class);
    }

    /**
     * Get the client's withdrawal requests
     */
    public function withdrawalRequests()
    {
        return $this->hasMany(ClientWithdrawalRequest::class);
    }

    /**
     * Get the client's support tickets
     */
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the client's course progress
     */
    public function courseProgress()
    {
        return $this->hasMany(ClientCourseProgress::class);
    }

    /**
     * Client's referral network entry
     */
    public function referralNetwork()
    {
        return $this->hasOne(ReferralNetwork::class);
    }

    /**
     * Get active invoice
     */
    public function activeInvoice()
    {
        return $this->invoices()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Get active invoices (plural)
     */
    public function activeInvoices()
    {
        return $this->invoices()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc');
    }

    /**
     * Check if client has access to courses
     */
    public function hasCourseAccess()
    {
        return $this->activeInvoice() && 
               $this->activeInvoice()->plan->has_courses;
    }

    /**
     * Get masked CPF (shows only last 4 digits)
     */
    public function getMaskedCpfAttribute()
    {
        if (!$this->cpf) return null;
        return '***.***.**' . substr($this->cpf, -4);
    }

    /**
     * Get masked email (shows only first part and domain)
     */
    public function getMaskedEmailAttribute()
    {
        if (!$this->email) return null;
        $parts = explode('@', $this->email);
        $username = $parts[0];
        $domain = $parts[1] ?? '';
        
        if (strlen($username) <= 2) {
            return $username . '@' . $domain;
        }
        
        return substr($username, 0, 2) . '***@' . $domain;
    }

    /**
     * Get total earnings
     */
    public function getTotalEarningsAttribute()
    {
        return $this->transactions()
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get total withdrawals
     */
    public function getTotalWithdrawalsAttribute()
    {
        return $this->transactions()
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get available balance
     */
    public function getAvailableBalanceAttribute()
    {
        return $this->total_earnings - $this->total_withdrawals;
    }

    /**
     * Check if account is locked
     */
    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Lock account
     */
    public function lockAccount($minutes = 30)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes)
        ]);
    }

    /**
     * Unlock account
     */
    public function unlockAccount()
    {
        $this->update([
            'locked_until' => null,
            'login_attempts' => 0
        ]);
    }

    /**
     * Increment login attempts
     */
    public function incrementLoginAttempts()
    {
        $attempts = $this->login_attempts + 1;
        $this->update(['login_attempts' => $attempts]);
        
        // Lock account after 5 failed attempts
        if ($attempts >= 5) {
            $this->lockAccount();
        }
    }

    /**
     * Reset login attempts
     */
    public function resetLoginAttempts()
    {
        $this->update([
            'login_attempts' => 0,
            'last_login_at' => now()
        ]);
    }

    /**
     * Activate client in the network
     */
    public function activateInNetwork()
    {
        if (!$this->active_network) {
            $this->update([
                'active_network' => true,
                'activated_at' => now(),
            ]);
        }
    }

    /**
     * Check if client is eligible for direct referral bonus
     * Direct referral bonus is always paid if they have purchased at least one invoice
     */
    public function isEligibleForDirectReferralBonus()
    {
        return $this->active_network;
    }

    /**
     * Check if client is eligible for other bonuses (requires active invoice)
     */
    public function isEligibleForBonuses()
    {
        return $this->activeInvoices()->exists();
    }
}
