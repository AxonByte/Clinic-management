<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    protected $table = 'hospitals';

    protected $fillable = [
        'name',
        'subscription_package',
        'subscription_state',
        'subscription_start_date',
        'subscription_end_date',
        'status',
    ];

    protected $casts = [
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
    ];

    /**
     * Get all users associated with this hospital.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'hospital_id');
    }

    /**
     * Get the super-admin user for this hospital, if defined.
     */
    public function superAdmin()
    {
        return $this->hasOne(User::class, 'hospital_id')->where('is_super_admin', true);
    }
}
