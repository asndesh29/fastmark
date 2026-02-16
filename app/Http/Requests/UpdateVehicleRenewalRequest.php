<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateVehicleRenewalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'renewals' => ['required', 'array', 'min:1'],
        ];

        $renewals = $this->input('renewals', []);

        foreach ($renewals as $slug) {

            if ($slug === 'insurance') {

                $rules["insurance.provider_id"] = ['required', 'exists:insurance_providers,id'];
                $rules["insurance.issue_date_bs"] = ['required', 'string'];
                $rules["insurance.expiry_date_bs"] = ['required', 'string'];
                $rules["insurance.insurance_type"] = ['required', 'in:general,third,partial'];
                $rules["insurance.payment_status"] = ['required', 'in:paid,unpaid'];

            } else {

                $rules["{$slug}.expiry_date_bs"] = ['required', 'string'];
                $rules["{$slug}.payment_status"] = ['required', 'in:paid,unpaid'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'renewals.required' => 'Please select at least one renewal type.',

            '*.expiry_date_bs.required' => 'Expiry Date is required.',
            '*.provider_id.required' => 'Provider is required.',
            '*.insurance_type.required' => 'Insurance Type is required.',
            '*.issue_date_bs.required' => 'Issue Date is required.',
            '*.payment_status.required' => 'Payment Status is required.',
        ];
    }
}
