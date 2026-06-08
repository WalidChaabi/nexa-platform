<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePaiement extends Model
{
    protected $table="invoices_paiements";
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'amount','date'
    ];

    public $timestamps=false;
}
