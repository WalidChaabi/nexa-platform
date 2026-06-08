<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Epingles extends Model
{
    public $timestamps=false;

    protected $fillable = [
        'name','color','store_id'
    ];
    public function store(){
        return $this->belongsTo(Store::class,'store_id','id');
    }
}
