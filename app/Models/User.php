<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: Satu User bisa memiliki banyak Loans (Peminjaman)
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::log ?? Loan::class);
    }

    // Relasi: Satu User bisa memiliki banyak ActivityLogs
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}