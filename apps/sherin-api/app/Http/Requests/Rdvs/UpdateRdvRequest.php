<?php

namespace App\Http\Requests\Rdvs;

use App\Models\Rdv;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRdvRequest extends FormRequest
{
    public function authorize(): bool
    {
        $rdv = $this->route('rdv');

        return $rdv instanceof Rdv
            && ($this->user()?->can('update', $rdv) ?? false);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'event_id' => ['sometimes', 'string', 'max:50'],
            'start' => ['sometimes', 'date'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ];
    }
}
