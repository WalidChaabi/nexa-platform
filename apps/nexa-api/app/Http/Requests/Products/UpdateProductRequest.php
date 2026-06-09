<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $product && $this->user()?->can('update', $product);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $storeId = $product ? (int) $product->store_id : 0;

        return [
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
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
}
