<?php

namespace App\Http\Requests\Dashboard;

use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class DashboardKpiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'store_id' => ['nullable', 'integer', 'exists:stores,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $user = $this->user();
            $storeId = $this->input('store_id');

            if ($user && $storeId !== null && ! StoreAccess::canAccessStore($user, (int) $storeId)) {
                $validator->errors()->add('store_id', 'You do not have access to this store.');
            }
        });
    }
}
