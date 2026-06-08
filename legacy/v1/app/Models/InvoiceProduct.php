<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = [
        'invoice_id', 'praticien_id', 'product_id','store_id' ,'qte', 'price'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
    public function praticien()
    {
        return $this->belongsTo(User::class, 'praticien_id', 'id');
    }
}
