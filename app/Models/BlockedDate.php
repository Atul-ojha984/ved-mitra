<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    use HasFactory;

    protected $fillable = ['pandit_profile_id', 'blocked_date', 'reason'];

    protected $casts = ['blocked_date' => 'date'];

    public function pandit()
    {
        return $this->belongsTo(PanditProfile::class, 'pandit_profile_id');
    }
}
