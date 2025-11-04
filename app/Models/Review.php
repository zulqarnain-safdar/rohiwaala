<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'farmer_id',
        'technician_id',
        'rating',
        'comment',
        'rating_breakdown',
        'is_verified',
    ];

    protected $casts = [
        'rating_breakdown' => 'array',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the job that owns the review.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the farmer that owns the review.
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    /**
     * Get the technician that owns the review.
     */
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}