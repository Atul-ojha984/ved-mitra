<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'pandit_profile_id', 'service_id',
        'booking_date', 'booking_time', 'booking_end_time', 'duration_hours',
        'address', 'status', 'total_amount', 'payment_status',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function pandit()  { return $this->belongsTo(PanditProfile::class, 'pandit_profile_id'); }
    public function service() { return $this->belongsTo(Service::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function review()  { return $this->hasOne(Review::class); }
    public function earning() { return $this->hasOne(Earning::class); }
}
