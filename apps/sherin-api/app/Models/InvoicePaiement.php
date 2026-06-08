<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePaiement extends Model
{
    protected $table = 'invoices_paiements';

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'amount',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'date' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
