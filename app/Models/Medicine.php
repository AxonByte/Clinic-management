<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['name','generic_name','category_id','purchase_price','sale_price','quantity','expiry_date','store_box','effects','company',
    ];

    public function category()
    {
        return $this->belongsTo(MedicineCategory::class, 'category_id');
    }

}
