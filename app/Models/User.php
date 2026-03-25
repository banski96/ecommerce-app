<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Custom primary key
    protected $primaryKey = 'user_id';

    // If your primary key is not auto-incrementing, uncomment:
    // public $incrementing = true; 

    // Fillable fields for mass assignment
    protected $fillable = [
        'fullName',
        'email',
        'password',
        'role',
    ];

    // Hidden fields for arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship: User has many orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: User has many sessions
     */
    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id', 'user_id');
    }
}