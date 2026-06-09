<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Product::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $storeId = (int) $this->input('store_id');

        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'label' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'promotion_price' => ['nullable', 'numeric', 'min:0'],
            'quantite' => ['nullable', 'integer', 'min:0'],
            'categorie_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query->where('store_id', $storeId),
                ),
            ],
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
