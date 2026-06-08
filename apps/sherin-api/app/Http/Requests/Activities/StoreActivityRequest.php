<?php

namespace App\Http\Requests\Activities;

use App\Models\Activity;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && StoreAccess::isAdmin($this->user())
            && $this->user()->can('create', Activity::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
