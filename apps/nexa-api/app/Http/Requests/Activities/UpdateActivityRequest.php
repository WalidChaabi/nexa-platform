<?php

namespace App\Http\Requests\Activities;

use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $activity = $this->route('activity');

        return $this->user() && StoreAccess::isAdmin($this->user())
            && $this->user()->can('update', $activity);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
