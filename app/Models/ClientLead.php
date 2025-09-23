<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLead extends Model
{
    use HasFactory;

    protected $table = 'client_leads';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'source',
        'status',
        'notes',
        'contacted',
        'contacted_at',
        'contact_notes',
        'converted',
        'converted_at',
        'conversion_value',
        'custom_fields',
    ];

    protected $casts = [
        'contacted' => 'boolean',
        'contacted_at' => 'datetime',
        'converted' => 'boolean',
        'converted_at' => 'datetime',
        'conversion_value' => 'decimal:2',
        'custom_fields' => 'array',
    ];

    /**
     * Get the client that owns the lead
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope for active leads
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive leads
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for pending leads
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for contacted leads
     */
    public function scopeContacted($query)
    {
        return $query->where('contacted', true);
    }

    /**
     * Scope for converted leads
     */
    public function scopeConverted($query)
    {
        return $query->where('converted', true);
    }

    /**
     * Mark lead as contacted
     */
    public function markAsContacted($notes = null)
    {
        $this->update([
            'contacted' => true,
            'contacted_at' => now(),
            'contact_notes' => $notes
        ]);
    }

    /**
     * Mark lead as converted
     */
    public function markAsConverted($value = null)
    {
        $this->update([
            'converted' => true,
            'converted_at' => now(),
            'conversion_value' => $value
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'pending' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Get status badge text
     */
    public function getStatusBadgeTextAttribute()
    {
        return match($this->status) {
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'pending' => 'Pendente',
            default => ucfirst($this->status)
        };
    }
}
