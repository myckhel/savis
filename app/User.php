<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'activation_token', 'lat', 'lng'
    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customers(){
      return $this->belongsToMany(Customer::class, 'user_customers')->withTimestamps();
    }

    public function metas(){
      return $this->hasMany(UserMeta::class);
    }

    // public function customer_services(){
    //   return $this->customers->services()->count();
    //   return $this->hasManyThrough(CustomerService::class, UserCustomer::class);
    // }

    public function payments(){
      return $this->customers->services;
      // (Service::class);
    }

    public function services(){
      return $this->hasMany(Service::class);
    }
}
