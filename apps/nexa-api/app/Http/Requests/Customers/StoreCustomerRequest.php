<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Customer::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'sexe' => ['nullable', 'integer', 'in:0,1'],
            'birth' => ['nullable', 'date'],
            'points' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $user = $this->user();
            $storeId = (int) $this->input('store_id');

            if ($user && ! StoreAccess::canAccessStore($user, $storeId)) {
                $validator->errors()->add('store_id', 'You do not have access to this store.');
            }
        });
    }
}
