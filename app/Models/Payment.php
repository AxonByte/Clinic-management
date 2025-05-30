<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $table = 'payments';

     protected $fillable = [
        'appointment_id',
        'amount',
        'deposit_type',
        'card_number',
        'expiry_date',
        'cvv',
        'is_paid',
        'status',
        'paid_at'
     ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
