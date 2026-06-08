<?php

namespace App\Http\Requests\Invoices;

use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Invoice::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $storeId = (int) $this->input('store_id');

        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'customer_id' => [
                'required',
                'integer',
                Rule::exists('customers', 'id')->where(fn ($query) => $query->where('store_id', $storeId)),
            ],
            'payment_method' => ['nullable', 'integer', 'between:0,2'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'total_ht' => ['nullable', 'numeric', 'min:0'],
            'tva' => ['nullable', 'numeric', 'min:0'],
            'for_sexe' => ['nullable', 'integer'],
            'description' => ['nullable', 'integer'],
            'points' => ['nullable', 'integer'],
            'reste_payer' => ['nullable', 'numeric', 'min:0'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->where(fn ($query) => $query->where('store_id', $storeId)),
            ],
            'products.*.qte' => ['required', 'integer', 'min:1'],
            'products.*.price' => ['nullable', 'numeric', 'min:0'],
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
