<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;  // <-- Import Module here

class SubscriptionPackage extends Model
{
    protected $table = 'subscription_packages';

    protected $fillable = [
        'package_name',
        'duration',
        'patient_limit',
        'doctor_limit',
        'original_price',
        'discounted_price',
        'module_ids',
        'show_in_frontend',
        'is_recommended',
    ];

    protected $casts = [
        'show_in_frontend' => 'boolean',
        'is_recommended' => 'boolean',
    ];

    public function getModuleIdsArrayAttribute()
    {
        return explode(',', $this->module_ids);
    }

    public function setModuleIdsArrayAttribute(array $value)
    {
        $this->attributes['module_ids'] = implode(',', $value);
    }
    
 public function getModulesAttribute()
{
    if (empty($this->module_ids)) {
        return collect();
    }

    $moduleIds = array_filter(array_map('trim', explode(',', $this->module_ids)));

    return Module::whereIn('id', $moduleIds)->get();
}
}
