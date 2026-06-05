<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'message',
        'role',
        'user_id'
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
