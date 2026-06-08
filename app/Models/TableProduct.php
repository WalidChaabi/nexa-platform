<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableProduct extends Model
{
    protected $fillable=[
        'user_id','product_id','table_id','store_id','status'
    ];
    public function ProductTable(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
