<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    protected $fillable = [
        'pandit_profile_id', 'booking_id', 'gross_amount',
        'commission_rate', 'commission_amount', 'net_amount', 'status', 'paid_at',
    ];

    protected $casts = ['paid_at' => 'datetime'];

    public function pandit()  { return $this->belongsTo(PanditProfile::class, 'pandit_profile_id'); }
    public function booking() { return $this->belongsTo(Booking::class); }
}
