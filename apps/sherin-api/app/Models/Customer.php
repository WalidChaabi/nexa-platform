<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'phone',
        'email',
        'firstname',
        'lastname',
        'sexe',
        'birth',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'birth' => 'date',
            'sexe' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
