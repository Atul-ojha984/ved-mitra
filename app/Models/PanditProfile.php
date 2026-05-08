<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanditProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bio', 'experience_years', 'qualification', 'specialization',
        'languages', 'consultation_fee', 'available_timings', 'gender', 'date_of_birth',
        'alternate_phone', 'address', 'city', 'state', 'pincode', 'location_lat',
        'location_lng', 'aadhaar_document', 'pan_document', 'certificate_document',
        'profile_photo', 'website_url', 'youtube_url', 'instagram_url', 'facebook_url',
        'verified', 'approval_status', 'rejection_reason', 'approved_at', 'approved_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'approved_at' => 'datetime',
        'verified' => 'boolean',
    ];

    /**
     * Only approved pandits with active user accounts.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved')
                     ->whereHas('user', fn($q) => $q->where('account_status', 'active'));
    }

    public function user()           { return $this->belongsTo(User::class); }
    public function services()       { return $this->belongsToMany(Service::class, 'pandit_services')->withPivot('custom_price')->withTimestamps(); }
    public function bookings()       { return $this->hasMany(Booking::class); }
    public function reviews()        { return $this->hasMany(Review::class); }
    public function approvedByAdmin(){ return $this->belongsTo(User::class, 'approved_by'); }
    public function availabilities() { return $this->hasMany(Availability::class); }
    public function blockedDates()   { return $this->hasMany(BlockedDate::class); }
    public function earnings()       { return $this->hasMany(Earning::class); }

    public function isApproved(): bool { return $this->approval_status === 'approved'; }
}
