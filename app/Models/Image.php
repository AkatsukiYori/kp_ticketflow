<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'filename',
        'file_path',
        'mime_type',
        'size'
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
