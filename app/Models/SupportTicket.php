<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets';

    protected $fillable = [
        'client_id',
        'admin_id',
        'ticket_number',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'assigned_at',
        'resolved_at',
        'resolution_notes',
        'attachments',
        'metadata',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'attachments' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the client that owns the ticket
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin assigned to the ticket
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the ticket responses
     */
    public function responses()
    {
        return $this->hasMany(SupportTicketResponse::class);
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'pending']);
    }

    /**
     * Scope for closed tickets
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    /**
     * Scope for high priority tickets
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Get priority badge color
     */
    public function getPriorityBadgeColorAttribute()
    {
        return match($this->priority) {
            'low' => 'secondary',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'dark',
            default => 'secondary'
        };
    }

    /**
     * Get priority badge text
     */
    public function getPriorityBadgeTextAttribute()
    {
        return match($this->priority) {
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
            'urgent' => 'Urgente',
            default => ucfirst($this->priority)
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'open' => 'primary',
            'in_progress' => 'warning',
            'pending' => 'info',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get status badge text
     */
    public function getStatusBadgeTextAttribute()
    {
        return match($this->status) {
            'open' => 'Aberto',
            'in_progress' => 'Em Andamento',
            'pending' => 'Pendente',
            'resolved' => 'Resolvido',
            'closed' => 'Fechado',
            default => ucfirst($this->status)
        };
    }

    /**
     * Generate unique ticket number
     */
    public static function generateTicketNumber()
    {
        do {
            $number = 'TKT-' . strtoupper(uniqid());
        } while (self::where('ticket_number', $number)->exists());
        
        return $number;
    }

    /**
     * Assign ticket to admin
     */
    public function assignToAdmin($adminId)
    {
        $this->update([
            'admin_id' => $adminId,
            'assigned_at' => now(),
            'status' => 'in_progress'
        ]);
    }

    /**
     * Resolve ticket
     */
    public function resolve($notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $notes
        ]);
    }

    /**
     * Close ticket
     */
    public function close()
    {
        $this->update([
            'status' => 'closed',
            'resolved_at' => now()
        ]);
    }

    /**
     * Get time since creation
     */
    public function getTimeSinceCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get time since last response
     */
    public function getTimeSinceLastResponseAttribute()
    {
        $lastResponse = $this->responses()->latest()->first();
        if (!$lastResponse) {
            return $this->created_at->diffForHumans();
        }
        
        return $lastResponse->created_at->diffForHumans();
    }
}
