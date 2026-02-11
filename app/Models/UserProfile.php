<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
    use HasFactory;
    protected $table = 'user_profiles';
    protected $fillable = [
        'user_id', 'gender', 'phone', 'avatar', 'birthday',
        'first_name', 'last_name', 'avatar_public_id'
    ];

    protected $casts = [
        'birthday' => 'date:Y-m-d',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) return null;

        if (str_contains($this->avatar, 'http')) {
            return $this->avatar;
        }

        return Storage::url($this->avatar);
    }
}
