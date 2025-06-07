<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable =[
        'madicine_id',
        'sale_id',
        'item_name',
        'company',
        'price',
        'quantity',
    ];
}
