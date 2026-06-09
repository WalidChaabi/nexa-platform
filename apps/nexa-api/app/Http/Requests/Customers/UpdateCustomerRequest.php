<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $customer = $this->route('customer');

        return $customer && $this->user()?->can('update', $customer);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'phone' => ['sometimes', 'required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'sexe' => ['nullable', 'integer', 'in:0,1'],
            'birth' => ['nullable', 'date'],
            'points' => ['nullable', 'string', 'max:50'],
        ];
    }
}
