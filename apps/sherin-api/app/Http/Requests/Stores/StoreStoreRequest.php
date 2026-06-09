<?php

namespace App\Http\Requests\Stores;

use App\Enums\UserRole;
use App\Models\Store;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Store::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                Rule::requiredIf(fn () => StoreAccess::isAdmin($this->user())),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->where('is_admin', UserRole::Owner->value),
                ),
            ],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'contact' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'invoice_message' => ['nullable', 'string', 'max:2000'],
            'sender_id' => ['nullable', 'string', 'max:20'],
            'activity_id' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }
}
