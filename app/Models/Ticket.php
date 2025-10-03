<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'name',
        'description',
        'status',
        'image_path',
        'created_by',
        'assigned_to',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    /**
     * Automatically generate a unique ticket_number on create
     */
    protected static function booted()
    {
        static::creating(function ($ticket) {
            if ($ticket->ticket_number) {
                do {
                    $candidate = str::upper(str::random(10));
                } while (static::where('ticket_number', $candidate)->exists());
                $ticket->ticket_number = $candidate;
            }
        });
}
    /**
     * Relationships
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    /**
     * Scope: tickets visible to a user
     */
    public function scopeVisibleTo($query, User $user)
    {
        return $query->where('created_by', $user->id)
                     ->orWhere('assigned_to', $user->id);
    }
}
