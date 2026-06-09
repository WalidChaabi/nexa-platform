<?php

namespace App\Http\Requests\CrossSelling;

use App\Models\CrossProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCrossProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $crossProduct = $this->route('crossProduct');

        return $crossProduct instanceof CrossProduct
            && ($this->user()?->can('update', $crossProduct) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $crossProduct = $this->route('crossProduct');

        return [
            'product_id' => [
                'sometimes',
                'integer',
                'exists:products,id',
                Rule::unique('cross_products', 'product_id')->ignore($crossProduct?->id),
            ],
            'message' => ['sometimes', 'string', 'max:500'],
            'days' => ['sometimes', 'integer', 'min:1', 'max:365'],
        ];
    }
}
