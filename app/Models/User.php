<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'password', 'role', 'has_paid'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'has_paid' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
