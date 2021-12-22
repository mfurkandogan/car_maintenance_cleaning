<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name', 'service_price', 'published'
    ];

    public function scopePublished($query)
    {
        $query->where('published', 1);
    }
}


