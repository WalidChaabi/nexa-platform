<?php

namespace App\Http\Requests\Reports;

use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
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
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'for_sexe' => ['nullable', 'integer', 'in:0,1,2'],
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
