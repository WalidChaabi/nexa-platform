<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{    
    protected $fillable = [
        'store_id', 'firstname', 'lastname', 'email', 'phone', 'birth', 'sexe', 'new',
    ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id');
    }

    public function getFullNameAttribute()	 
    {	 
        $name = ($this->firstname) ? $this->firstname : '';
        $lastname = ($this->lastname) ? $this->lastname : '';
        return $name.'-'.$lastname;	 	    
    }

    public function invoices(){
        return $this->hasMany(Invoice::class,'customer_id');
    }
}

