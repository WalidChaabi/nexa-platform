<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageCustomer extends Model
{
    protected $table="messages_customers";

    protected $fillable = [
        'store_id',
        'customer_id',
        'message',
        'payed',
        'type',
        'status',
    ];
    public function store(){
        return $this->belongsTo(Store::class,'store_id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
}
