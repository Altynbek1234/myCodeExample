<?php

namespace App\Http\Requests\ApplicantLegalEntity;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantLEAddressRequest extends FormRequest
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
//            'soate_id' => 'exists:soates,id',
//            'legal_address' => 'string|max:255',
//            'postal_address' => 'string|max:255',
        ];
    }
}
