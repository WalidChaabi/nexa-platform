<?php

namespace App\Http\Requests\Owners;

use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOwnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && StoreAccess::isAdmin($this->user());
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'allow_share' => ['nullable', 'boolean'],
        ];
    }
}
