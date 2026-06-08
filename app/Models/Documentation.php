<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description'
    ];

    public function category() {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function documentation_file() {
        return $this->hasOne(DocumentationFile::class, 'id');
    }
}
