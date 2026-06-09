<?php

namespace App\Http\Requests\Employers;

use App\Enums\UserRole;
use App\Models\User;
use App\Support\Authorization\StoreAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var User|null $employer */
        $employer = $this->route('employer');

        if (! $user || ! $employer instanceof User) {
            return false;
        }

        return $this->canManageEmployer($user, $employer);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes', 'integer', Rule::exists('stores', 'id')],
            'firstname' => ['sometimes', 'string', 'max:255'],
            'lastname' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:20'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'max:255'],
            'allow_share' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $user = $this->user();
            /** @var User|null $employer */
            $employer = $this->route('employer');

            if (! $user || ! $employer instanceof User) {
                return;
            }

            if ($this->has('store_id')) {
                $storeId = $this->integer('store_id');

                if (! StoreAccess::isAdmin($user) && $user->role() === UserRole::Owner) {
                    if (! StoreAccess::canAccessStore($user, $storeId)) {
                        $validator->errors()->add('store_id', 'You cannot assign this store.');
                    }
                }
            }
        });
    }

    private function canManageEmployer(User $actor, User $employer): bool
    {
        if ((int) $employer->is_admin !== UserRole::Employer->value) {
            return false;
        }

        if (StoreAccess::isAdmin($actor)) {
            return true;
        }

        if ($actor->role() !== UserRole::Owner || ! $employer->store_id) {
            return false;
        }

        return StoreAccess::canAccessStore($actor, (int) $employer->store_id);
    }
}
