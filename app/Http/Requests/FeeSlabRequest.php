<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeSlabRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_type' => 'required|string|max:255',
            'min_cc' => 'required|integer|min:0, max:4',
            'max_cc' => 'required|integer|min:0, max:4',
            'base_fee' => 'required|numeric|min:0, max:99999.99',
        ];
    }
}
