<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProduct extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'qte',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'qte' => 'integer',
            'price' => 'float',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
