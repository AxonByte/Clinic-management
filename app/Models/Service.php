<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'service_duration',
        'default_price',
        'service_type',
        'description',
        'has_discount',
        'discount_type', 'department_id','hospital_id',
        'discount_value',
        'status',
    ];

    // Relationships
    // public function category()
    // {
        // return $this->belongsTo(Category::class);
    // }

    // public function subcategory()
    // {
        // return $this->belongsTo(Subcategory::class);
    // }

  public function department()
{
    return $this->belongsTo(Department::class);
}
}
