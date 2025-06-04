<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TakenService extends Model
{
    protected $fillable = ['patient_admitted_id','nurse_id','service_id','sales_price','quantity','total'];

    public function nurse() {
        return $this->belongsTo(User::class,'nurse_id');
    }
    public function service() // not services
    {
        return $this->belongsTo(PatientService::class, 'service_id');
    }

}
