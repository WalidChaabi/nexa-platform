<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InitialFond extends Model
{
    public $timestamps = false;

    protected $fillable = ['store_id' , 'value' , 'date' ];
}
