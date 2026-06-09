<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'store_id',
        'is_admin',
        'firstname',
        'lastname',
        'phone',
        'email',
        'password',
        'allow_share',
        'status',
        'is_active',
        'last_activity',
    ];

    protected $hidden = [
        'password',
        'pos_token_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_activity' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'integer',
            'is_active' => 'integer',
            'status' => 'integer',
            'allow_share' => 'integer',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->firstname} {$this->lastname}");
    }

    public function role(): UserRole
    {
        return UserRole::fromLegacy((int) $this->is_admin);
    }

    public function isActive(): bool
    {
        return (int) $this->is_active === 1 && (int) $this->status === 1;
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
