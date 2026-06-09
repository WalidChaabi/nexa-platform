<?php

namespace App\Http\Requests\Employers;

use App\Enums\UserRole;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        if (StoreAccess::isAdmin($user)) {
            return true;
        }

        if ($user->role() !== UserRole::Owner) {
            return false;
        }

        $storeId = $this->integer('store_id');

        return $storeId > 0 && StoreAccess::canAccessStore($user, $storeId);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', Rule::exists('stores', 'id')],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'allow_share' => ['nullable', 'boolean'],
        ];
    }
}
