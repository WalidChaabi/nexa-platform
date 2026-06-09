<?php

namespace App\Http\Requests\Campaigns;

use App\Models\CampaignDetail;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', CampaignDetail::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'reduction' => ['required', 'integer', 'min:0', 'max:100'],
            'active' => ['nullable', 'integer', 'in:0,1'],
            'filter' => ['required', 'array'],
            'filter.age_min' => ['required', 'integer', 'min:0', 'max:120'],
            'filter.age_max' => ['required', 'integer', 'min:0', 'max:120', 'gte:filter.age_min'],
            'filter.sexe' => ['required', 'string', 'in:both,male,female,0,1,2'],
            'filter.points' => ['required', 'integer', 'min:0'],
            'filter.last_visit' => ['required', 'integer', 'min:0'],
            'filter.heure_start' => ['required', 'string', 'max:8'],
            'filter.heure_end' => ['required', 'string', 'max:8'],
            'messages' => ['nullable', 'array'],
            'messages.*.message' => ['required_with:messages', 'string', 'max:500'],
            'messages.*.date_start' => ['nullable', 'integer', 'min:0'],
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
