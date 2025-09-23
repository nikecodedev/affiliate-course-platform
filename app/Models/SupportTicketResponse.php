<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketResponse extends Model
{
    use HasFactory;

    protected $table = 'support_ticket_responses';

    protected $fillable = [
        'ticket_id',
        'client_id',
        'admin_id',
        'message',
        'is_internal',
        'attachments',
        'metadata',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'attachments' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the ticket that owns the response
     */
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the client who made the response
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the admin who made the response
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the responder (client or admin)
     */
    public function getResponderAttribute()
    {
        if ($this->admin_id) {
            return $this->admin;
        }
        
        return $this->client;
    }

    /**
     * Get responder name
     */
    public function getResponderNameAttribute()
    {
        if ($this->admin_id) {
            return $this->admin->name . ' (Admin)';
        }
        
        return $this->client->name;
    }

    /**
     * Get responder avatar
     */
    public function getResponderAvatarAttribute()
    {
        if ($this->admin_id) {
            return 'admin';
        }
        
        return 'client';
    }

    /**
     * Scope for internal responses
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope for external responses
     */
    public function scopeExternal($query)
    {
        return $query->where('is_internal', false);
    }
}
