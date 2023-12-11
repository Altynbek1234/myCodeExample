<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationArrayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'organizations' => 'required|array',
            'organizations.*.id' => 'nullable|exists:government_agencies,id',
            'organizations.*.defendents' => 'nullable|array',
            'organizations.*.defendents.*' => 'nullable|exists:defendents',
        ];
    }
}
