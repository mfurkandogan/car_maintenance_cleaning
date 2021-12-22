<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id'];

    public function scopePublished($query)
    {
        $query->where('published', 1);
    }
}


