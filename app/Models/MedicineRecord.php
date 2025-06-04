<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineRecord extends Model
{
    protected $fillable = [
        'patient_admitted_id',
        'generic_name',
        'brand_name',
        'invoice_no',
        'sales_price',
        'quantity',
        'total'
    ];

    public function medicineGen() {
        return $this->belongsTo(MedicineGen::class);
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }

}
