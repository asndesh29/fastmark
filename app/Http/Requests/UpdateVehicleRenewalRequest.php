<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\RenewalType;

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
            'renewals' => ['required', 'array'],
            'renewals.*' => ['string'],
        ];

        $renewals = $this->input('renewals', []);

        foreach ($renewals as $slug) {
            $fields = $this->getRenewalFields($slug);

            foreach ($fields as $field) {
                $name = $slug . '.' . $field['name'];

                switch ($field['type']) {
                    case 'select':
                        $rules[$name] = ['required'];
                        break;
                    case 'date':
                        $rules[$name] = ['required', 'string'];
                        break;
                    default:
                        $rules[$name] = ['nullable', 'string'];
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'renewals.required' => 'Please select at least one renewal type.',
            'renewals.array' => 'Invalid selection of renewal types.',
        ];

        $renewals = $this->input('renewals', []);

        foreach ($renewals as $slug) {
            $fields = $this->getRenewalFields($slug);

            foreach ($fields as $field) {
                $name = $slug . '.' . $field['name'];
                $messages["{$name}.required"] = "{$field['label']} is required for {$slug}.";
            }
        }

        return $messages;
    }

    /**
     * Build dynamic fields for validation
     */
    protected function getRenewalFields($slug)
    {
        $fields = [];

        // Common fields for all types
        $fields[] = ['name' => 'expiry_date_bs', 'label' => 'Expiry Date', 'type' => 'date'];
        $fields[] = ['name' => 'payment_status', 'label' => 'Payment Status', 'type' => 'select'];
        $fields[] = ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'text'];

        // Special fields for insurance dynamically
        if ($slug === 'insurance') {
            $fields[] = ['name' => 'provider_id', 'label' => 'Insurance Provider', 'type' => 'select'];
            // $fields[] = ['name' => 'issue_date_bs', 'label' => 'Issue Date', 'type' => 'date'];
            $fields[] = ['name' => 'insurance_type', 'label' => 'Insurance Type', 'type' => 'select'];
            $fields[] = ['name' => 'policy_number', 'label' => 'Policy Number', 'type' => 'text'];
        }

        return $fields;
    }
}
