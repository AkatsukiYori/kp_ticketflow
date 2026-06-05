<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'filename',
        'file_path',
        'mime_type',
        'size'
    ];

    public function documentation() {
        return $this->belongsTo(Documentation::class, 'document_id');
    }
}
