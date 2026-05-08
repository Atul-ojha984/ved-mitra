<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'pandit_profile_id', 'day_of_week', 'start_time', 'end_time', 'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function pandit()
    {
        return $this->belongsTo(PanditProfile::class, 'pandit_profile_id');
    }

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? '';
    }
}
