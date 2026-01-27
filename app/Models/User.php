<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->getIsAdmin($value),
            set: fn ($value) => $this->setIsAdmin($value),
        );
    }

    // is_admin nullable в БД
    private function getIsAdmin(?string $value)
    {
        return $value ? 'Admin' : 'User';
    }

    private function setIsAdmin($value)
    {
        return $value === 'on' || $value === 1 || $value === '1' ? 1 : 0;
    }
}
