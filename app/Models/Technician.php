<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'father_name',
        'cnic',
        'date_of_birth',
        'home_address',
        'clinic_address',
        'province',
        'division',
        'district',
        'tehsil_city',
        'degree_year',
        'cnic_front_image',
        'cnic_back_image',
        'degree_front_image',
        'degree_back_image',
        'years_of_experience',
        'customers_per_month',
        'areas_of_expertise',
        'mobile_1',
        'mobile_2',
        'clinic_latitude',
        'clinic_longitude',
        'unique_code',
        'status',
        'availability',
        'working_hours_start',
        'working_hours_end',
        'available_24_7',
        'service_areas',
        'admin_comments',
        'admin_signature',
    ];

    protected $casts = [
        'working_hours_start' => 'datetime',
        'working_hours_end' => 'datetime',
        'available_24_7' => 'boolean',
        'service_areas' => 'array',
    ];

    /**
     * Get the user that owns the technician.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the jobs for the technician.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the payments for the technician.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the reviews for the technician.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the semen products managed by the technician.
     */
    public function semenProducts()
    {
        return $this->belongsToMany(BullSemen::class, 'bull_semen_technician');
    }
}