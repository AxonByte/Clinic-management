<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable =[
        'madicine_id',
        'subtotal',
        'discount',
        'total',
    ];

    // App\Models\Sales
public function items()
{
    return $this->hasMany(SaleItem::class, 'sale_id');
}

}
