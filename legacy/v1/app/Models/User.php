<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'phone', 'store_id', 'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pos_token_hash', 'token',
    ];

    public function getFullNameAttribute()	 	 
    {	 	 
     return $this->firstname . " " . $this->lastname;	 	 
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class,'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class,'user_id');
    }
    public function invoicesProduit()
    {
        return $this->hasMany(InvoiceProduct::class,'praticien_id');
    }
   
}
