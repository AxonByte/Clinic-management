<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = ['bed_number','category_id','description',
    ];

    public function category()
    {
        return $this->belongsTo(BedCategory::class, 'category_id');
    }
}
