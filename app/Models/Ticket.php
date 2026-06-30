<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assign_to',
        'category_id',
        'department_id',
        'member_id',
        'ticket_no',
        'ticket_title',
        'problem',
        'no_wa',
        'report_date',
        'location',
        'priority',
        'note',
        'status_ticket',
        'status_reason',
        'closed_at',
        'estimate',
        'reject_at',
        'modul',
        'sub_modul',
        'reopened_at',
        'ikb_status_point',
        'expired_at'
    ];

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function category() {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function member() {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'assign_to');
    }

    public function ticket_file() {
        return $this->hasOne(Image::class, 'ticket_id');
    }

    public function log() {
        return $this->hasMany(Log::class, 'ticket_id');
    }

    public function rating() {
        return $this->hasOne(Rating::class, 'ticket_id');
    }

    public function feedback() {
        return $this->hasMany(TicketFeedback::class, 'ticket_id');
    }
}
