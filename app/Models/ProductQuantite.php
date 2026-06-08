<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuantite extends Model
{
    //  public $timestamps=false;
    protected $table ="product_quantites";


    protected $fillable = [
        'quantite',
    ];
    public function productQuantiteDetails(){
        return $this->hasMany(ProductQuantite::class,'product_quantite_id');
    }
}
