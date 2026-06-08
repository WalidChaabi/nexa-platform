<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    public $timestamps=false;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','daily','weekly', "monthly"
    ];

    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
