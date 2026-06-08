<?php

namespace App\Http\Requests\Rdvs;

use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreRdvRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Rdv::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'title' => ['required', 'string', 'max:255'],
            'event_id' => ['required', 'string', 'max:50'],
            'start' => ['required', 'date'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $user = $this->user();
            $customerId = (int) $this->input('customer_id');

            if (! $user || $customerId <= 0) {
                return;
            }

            $storeId = (int) \App\Models\Customer::query()->whereKey($customerId)->value('store_id');

            if ($storeId && ! StoreAccess::canAccessStore($user, $storeId)) {
                $validator->errors()->add('customer_id', 'You do not have access to this customer.');
            }
        });
    }
}
