<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Admin who performed the action
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope for specific actions
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific model type
     */
    public function scopeModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted action description
     */
    public function getFormattedActionAttribute()
    {
        return match($this->action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'failed_login' => 'Failed Login Attempt',
            'password_changed' => 'Password Changed',
            '2fa_enabled' => '2FA Enabled',
            '2fa_disabled' => '2FA Disabled',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get action badge class
     */
    public function getActionBadgeClassAttribute()
    {
        return match($this->action) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'failed_login' => 'danger',
            'password_changed' => 'warning',
            '2fa_enabled' => 'success',
            '2fa_disabled' => 'warning',
            default => 'secondary',
        };
    }
}

