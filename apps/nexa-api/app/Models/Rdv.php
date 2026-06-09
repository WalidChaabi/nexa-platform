<?php

namespace App\Models;

use App\Enums\RdvStatus;
use App\Models\Concerns\BelongsToStore;
use Database\Factories\RdvFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rdv extends Model
{
    /** @use HasFactory<RdvFactory> */
    use BelongsToStore, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'store_id',
        'event_id',
        'title',
        'start',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'status' => RdvStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
