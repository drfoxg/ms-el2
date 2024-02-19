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
    use HasApiTokens, HasFactory, Notifiable;

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

        //dd('isAdmin');

        return Attribute::make(
            get: fn (string $value) => $this->getIsAdmin($value),
            set: fn ($value) => $this->setIsAdmin($value),
        );
    }

    private function getIsAdmin(string $value)
    {
        if ($value) {
            $value = 'Admin';
        } else {
            $value = 'User';
        }

        return $value;
    }

    private function setIsAdmin($value)
    {
        //dd($value);

        if ($value == 'on' || $value = 1) {
            $value = 1;
        } else {
            $value = 0;
        }

        return $value;
    }
}
