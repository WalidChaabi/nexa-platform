<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //  public $timestamps=false;

    protected $fillable = [
        'label','price', "store_id",'categorie_id','quantite','promotion_price','image',
    ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id');
    }
    
    public function invoicesProduct(){
        return $this->hasMany('App\Models\InvoiceProduct')->with('invoice');
    }

    public function crossProduct(){
        return $this->hasOne(CrossProduct::class,'product_id');
    }

    public function categorie(){
        return $this->belongsTo(Categorie::class,'categorie_id');
    }
}
