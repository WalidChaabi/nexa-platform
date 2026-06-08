<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{

    public $timestamps=false;
    
    protected $fillable = [
        'store_id','name','description'
    ];

    
    public function products(){
        return $this->hasMany(Product::class);
    }
}
