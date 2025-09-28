<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'document',
        'birth_date',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'is_affiliate',
        'affiliate_code',
        'referral_code',
        'is_active',
        'active_network',
        'email_verified_at',
        'matrix_parent_id',
        'matrix_position',
        'matrix_level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'is_affiliate' => 'boolean',
        'is_active' => 'boolean',
        'active_network' => 'boolean',
    ];

    /**
     * User sales (as customer)
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Affiliate sales (sales made by this user as affiliate)
     */
    public function affiliateSales(): HasMany
    {
        return $this->hasMany(Sale::class, 'affiliate_id');
    }

    /**
     * Referred users
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referral_code', 'affiliate_code');
    }

    /**
     * Referrer user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referral_code', 'affiliate_code');
    }

    /**
     * Commission payments
     */
    public function commissionPayments(): HasMany
    {
        return $this->hasMany(CommissionPayment::class);
    }

    /**
     * Withdrawals
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for affiliates
     */
    public function scopeAffiliates($query)
    {
        return $query->where('is_affiliate', true);
    }

    /**
     * Generate unique affiliate code
     */
    public function generateAffiliateCode()
    {
        do {
            $code = strtoupper(substr(md5($this->email . time()), 0, 8));
        } while (static::where('affiliate_code', $code)->exists());

        return $code;
    }

    /**
     * Get total commission earned
     */
    public function getTotalCommissionEarnedAttribute()
    {
        return $this->affiliateSales()->confirmed()->sum('commission_amount');
    }

    /**
     * Get total commission paid
     */
    public function getTotalCommissionPaidAttribute()
    {
        return $this->commissionPayments()->where('status', 'paid')->sum('amount');
    }

    /**
     * Get pending commission
     */
    public function getPendingCommissionAttribute()
    {
        return $this->total_commission_earned - $this->total_commission_paid;
    }

    /**
     * Get total sales count
     */
    public function getTotalSalesCountAttribute()
    {
        return $this->affiliateSales()->confirmed()->count();
    }

    /**
     * Get total sales amount
     */
    public function getTotalSalesAmountAttribute()
    {
        return $this->affiliateSales()->confirmed()->sum('amount');
    }

    /**
     * Check if user is an affiliate
     */
    public function isAffiliate()
    {
        return $this->is_affiliate;
    }

    /**
     * Check if user has pending commission
     */
    public function hasPendingCommission()
    {
        return $this->pending_commission > 0;
    }

    /**
     * Get the lessons the user has access to
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
                    ->withPivot(['completed', 'completed_at'])
                    ->withTimestamps();
    }

    /**
     * Get the user's invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the user's sponsor (referrer)
     */
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'referral_code', 'affiliate_code');
    }

    /**
     * Get matrix parent (in forced matrix structure)
     */
    public function matrixParent()
    {
        return $this->belongsTo(User::class, 'matrix_parent_id');
    }

    /**
     * Get matrix children (in forced matrix structure)
     */
    public function matrixChildren()
    {
        return $this->hasMany(User::class, 'matrix_parent_id');
    }

    /**
     * Get all matrix descendants
     */
    public function matrixDescendants()
    {
        return $this->hasMany(User::class, 'matrix_parent_id')
            ->with('matrixDescendants');
    }

    /**
     * Get the user's bonus payments
     */
    public function bonusPayments()
    {
        return $this->hasMany(BonusPayment::class, 'client_id');
    }

    /**
     * Check if user has active invoice
     */
    public function hasActiveInvoice()
    {
        return $this->invoices()
            ->where('status', 'paid')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get user's course progress
     */
    public function getCourseProgress($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return 0;
        }

        $totalLessons = $course->modules()
            ->withCount('lessons')
            ->get()
            ->sum('lessons_count');

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->lessons()
            ->whereHas('module', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('completed', true)
            ->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }
}

