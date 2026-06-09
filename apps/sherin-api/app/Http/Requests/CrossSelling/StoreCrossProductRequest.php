<?php

namespace App\Http\Requests\CrossSelling;

use App\Models\CrossProduct;
use App\Models\Product;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreCrossProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', CrossProduct::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id', 'unique:cross_products,product_id'],
            'message' => ['required', 'string', 'max:500'],
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $user = $this->user();
            $productId = (int) $this->input('product_id');

            if (! $user || $productId <= 0) {
                return;
            }

            $storeId = (int) Product::query()->whereKey($productId)->value('store_id');

            if ($storeId && ! StoreAccess::canAccessStore($user, $storeId)) {
                $validator->errors()->add('product_id', 'You do not have access to this product.');
            }
        });
    }
}
