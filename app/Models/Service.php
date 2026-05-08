<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'duration_hours',
        'image_url',
    ];

    public function pandits()
    {
        return $this->belongsToMany(PanditProfile::class, 'pandit_services')
                    ->withPivot('custom_price')
                    ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
