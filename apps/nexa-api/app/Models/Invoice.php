<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\BelongsToStore;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use BelongsToStore, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'store_id',
        'user_id',
        'employ_id',
        'customer_id',
        'for_sexe',
        'description',
        'invoice_date',
        'total',
        'total_ht',
        'tva',
        'paid_amount',
        'payment_method',
        'points',
        'status',
        'reste',
        'reste_payer',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'datetime',
            'total' => 'float',
            'total_ht' => 'float',
            'tva' => 'float',
            'paid_amount' => 'float',
            'reste' => 'float',
            'reste_payer' => 'float',
            'status' => 'integer',
            'payment_method' => 'integer',
            'points' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employ_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(InvoicePaiement::class);
    }

    public function statusEnum(): InvoiceStatus
    {
        return InvoiceStatus::from($this->status);
    }

    public function isPending(): bool
    {
        return $this->status === InvoiceStatus::Pending->value;
    }
}
