<?php

namespace App\Http\Requests\Stores;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $store = $this->route('store');

        return $store && $this->user()?->can('update', $store);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
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
