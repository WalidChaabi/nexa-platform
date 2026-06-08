<?php

namespace App\models;

use App\Models\Epingles;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
     
    protected $fillable = [
        'user_id','activity_id','logo','sender_id' ,'name','contact','phone','address','base','base_profit','coeff',
        'tva','invoice_message','reste','rendez_vous','praticien_1','praticien_2','RA','POS','QG','Anniv','Table'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class,'store_id');
    }
    
    public function epingles(){
        return $this->hasMany(Epingles::class,'store_id');
    }
}
