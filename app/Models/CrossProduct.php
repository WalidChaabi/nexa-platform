<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrossProduct extends Model
{
      
    protected $fillable = [
        'product_id','message','days' 
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function sms(){
        return $this->hasMany(SmsCrossSelling::class,'cross_product_id');
    }
}
