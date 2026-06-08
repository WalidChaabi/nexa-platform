<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps=false;

    protected $fillable = [
        'user_id','store_id','employ_id','promotion_price','description' ,'store_id','customer_id','invoice_date','total','paid_amount','mode_payment','points','for_sexe','reste','reste_payer','payment_method'
    ];

    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id','id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceProduct::class,'invoice_id','id');
    }

    // public function fonds()
    // {
    //     return $this->hasMany(Fond::class,'invoice_id');
    // }

    public function store(){
        return $this->belongsTo('App\Models\Store','store_id','id');
    }

    public function employer(){
        return $this->belongsTo('App\User','employ_id','id');
    }
    
    public function campaign(){
        return $this->belongsTo('App\Models\CampaignHistorique','id','invoice_id');
    }
    
}
