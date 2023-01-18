<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

use Wm\WmPackage\Model\User as Authenticable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'hoqu_api_token',
        'hoqu_roles',
        'hoqu_processor_capabilites',
        'endpoint'
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
        'hoqu_processor_capabilites' => 'array', //TODO: add custom casting with enum
        'hoqu_roles' => 'array' //TODO: add custom casting with enum
    ];


    /**
     * CHATGPT:
     * This code is a method of a class that creates a relationship between
     * the class and the HoquJob model.
     * The method returns all HoquJob objects associated with the processor_id of the class.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hoquJobs()
    {
        return $this->hasMany(HoquJob::class, 'processor_id');
    }
}
