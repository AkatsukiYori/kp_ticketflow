<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name'
    ];
    
    public function documentation() {
        return $this->hasMany(Documentation::class, 'category_id', 'id');
    }

    public function ticket() {
        return $this->hasMany(Ticket::class, 'category_id', 'id');
    }
}
