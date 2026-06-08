<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHestorique extends Model
{
    //  public $timestamps=false;
    protected $table ="product_hestoriques";


    protected $fillable = [
        'store_id', 'user_id', 'product_id', 'notification',
    ];
    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

}
