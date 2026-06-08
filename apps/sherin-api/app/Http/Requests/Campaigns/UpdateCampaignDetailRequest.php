<?php

namespace App\Http\Requests\Campaigns;

use App\Models\CampaignDetail;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        $detail = $this->route('campaignDetail');

        return $detail instanceof CampaignDetail
            && ($this->user()?->can('update', $detail) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'campaign_id' => ['sometimes', 'integer', 'exists:campaigns,id'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'reduction' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'active' => ['sometimes', 'integer', 'in:0,1'],
            'filter' => ['sometimes', 'array'],
            'filter.age_min' => ['required_with:filter', 'integer', 'min:0', 'max:120'],
            'filter.age_max' => ['required_with:filter', 'integer', 'min:0', 'max:120'],
            'filter.sexe' => ['required_with:filter', 'string', 'in:both,male,female,0,1,2'],
            'filter.points' => ['required_with:filter', 'integer', 'min:0'],
            'filter.last_visit' => ['required_with:filter', 'integer', 'min:0'],
            'filter.heure_start' => ['required_with:filter', 'string', 'max:8'],
            'filter.heure_end' => ['required_with:filter', 'string', 'max:8'],
            'messages' => ['nullable', 'array'],
            'messages.*.message' => ['required_with:messages', 'string', 'max:500'],
            'messages.*.date_start' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $detail = $this->route('campaignDetail');
            if (! $detail instanceof CampaignDetail) {
                return;
            }

            $user = $this->user();
            if ($user && ! StoreAccess::canAccessStore($user, (int) $detail->store_id)) {
                $validator->errors()->add('store_id', 'You do not have access to this store.');
            }
        });
    }
}
