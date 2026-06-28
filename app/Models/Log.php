<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'status',
        'action_type',
        'log_date',
        'description',
        'auto_closed',
        'closed_by'
    ];

    protected $appends = [
        'created_by'
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // START: Accessor
    public function getCreatedByAttribute() {
        if($this->user_id === null) {
            return $this->ticket?->member?->username;
        }

        return $this->ticket?->users?->username;
    }
    // END: Accessor
}
