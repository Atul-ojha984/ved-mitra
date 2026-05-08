<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'account_status', 'avatar', 'google_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Relationships ──────────────────────────────
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function panditProfile()
    {
        return $this->hasOne(PanditProfile::class);
    }

    public function kundlis()
    {
        return $this->hasMany(Kundli::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    // ─── Helpers ────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f97316&color=fff&size=128';
    }
}
