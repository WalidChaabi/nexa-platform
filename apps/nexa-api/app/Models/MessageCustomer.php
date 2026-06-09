<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageCustomer extends Model
{
    use BelongsToStore;

    protected $table = 'messages_customers';

    protected $fillable = [
        'customer_id',
        'store_id',
        'message',
        'type',
        'payed',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payed' => 'integer',
            'status' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
