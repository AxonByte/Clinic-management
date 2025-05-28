<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseRecord extends Model
{

    protected $table = 'case_records';

    protected $fillable = ['date', 'patient_id','title','symptom_id','diagnosis_id','advice_id','lab_test_id','treatment_id','history'];


    public function patient(){ 
        return $this->belongsTo(User::class, 'patient_id');
     }

    public function symptom() {
     return $this->belongsTo(Symptom::class);
    }

    public function diagnosis() {
      return $this->belongsTo(Diagnosis::class);
    }

    public function advice(){ 
        return $this->belongsTo(Advice::class);
     }

    public function labTest(){
        return $this->belongsTo(LabTest::class); 
    }
    
    public function treatment() {
     return $this->belongsTo(Treatment::class);
    }

}
