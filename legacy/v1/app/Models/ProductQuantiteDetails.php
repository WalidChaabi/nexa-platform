<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuantiteDetails extends Model
{
    //  public $timestamps=false;
    protected $table ="product_quantite_details";


    protected $fillable = [
        'store_id','product_id',"product_quantite_id"
    ];
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function productQuantite(){
        return $this->belongsTo(ProductQuantite::class,'product_quantite_id');
    }
}
