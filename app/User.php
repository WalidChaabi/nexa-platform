<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'phone', 'store_id', 'address', 'last_activity',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFullNameAttribute()	 	 
    {
        $name = ($this->firstname) ? $this->firstname : '';
        $lastname = ($this->lastname) ? $this->lastname : '';
     return $name.'-'.$lastname;	 	 
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id');
    }

    public function stores()
    {
        return $this->hasMany('App\Models\Store','user_id');
    }

   

}
