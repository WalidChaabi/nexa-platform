<?php

namespace App\Http\Requests\Owners;

use App\Enums\UserRole;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $owner = $this->route('owner');

        return $this->user()
            && StoreAccess::isAdmin($this->user())
            && $owner
            && (int) $owner->is_admin === UserRole::Owner->value;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $ownerId = $this->route('owner')?->id;

        return [
            'firstname' => ['sometimes', 'required', 'string', 'max:255'],
            'lastname' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'allow_share' => ['nullable', 'boolean'],
        ];
    }
}
