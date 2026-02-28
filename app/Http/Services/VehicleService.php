<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use App\Helpers\AppHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleService
{
    public function list(Request $request, $perPage = null)
    {
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType'])
            // Filter by customer name
            ->when($request->customer, function ($query, $customer) {
                $query->whereHas('owner', function ($q) use ($customer) {
                    $q->where('first_name', 'like', "%{$customer}%")
                        ->orWhere('last_name', 'like', "%{$customer}%");
                });
            })
            // Filter by registration number
            ->when($request->registration_no, function ($query, $registration_no) {
                $query->where('registration_no', 'like', "%{$registration_no}%");
            })
            // Filter by status
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->where('is_active', (int) $request->status);
            })
            // Filter by vehicle type
            ->when($request->filled('vehicle_type_id'), function ($query) use ($request) {
                $query->where('vehicle_type_id', $request->vehicle_type_id);
            })
            // Filter by vehicle category
            ->when($request->filled('vehicle_category_id'), function ($query) use ($request) {
                $query->where('vehicle_category_id', $request->vehicle_category_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $vehicles;
    }

    public function store($data)
    {
        return Vehicle::create($data);
    }

    public function getById($id)
    {
        return Vehicle::findOrFail($id);
    }

    public function update(Vehicle $vehicle, $data)
    {
        return $vehicle->update($data);
    }

    public function destroy($id)
    {
        $vehicle = $this->getById($id);

        if (!$vehicle) {
            return false;
        }

        return $vehicle->delete();
    }

    public function updateRenewal1(array $data)
    {
        // dd($data);
        $renewalType = RenewalType::where('slug', $data['type'])->first();

        if (!$renewalType) {
            throw new \Exception("Renewal type '{$data['type']}' not found.");
        }

        DB::beginTransaction();

        try {

            $modelClass = $this->resolveRenewableModel($data['type']);
            // dd($modelClass);

            if (!$modelClass) {
                throw new \Exception("Invalid renewal model for type '{$data['type']}'.");
            }

            $invoiceNumber = AppHelper::generateInvoiceNumber($data['type']);

            if ($data['type'] == 'insurance') {
                $expiryDate = $this->calculateExpiryDate($data['issue_date']);
            } else {
                $expiryDate = $this->calculateExpiryDate($data['last_expiry_date']);
            }

            $renewable = $modelClass::create([
                'vehicle_id' => $data['vehicle_id'],
                'invoice_no' => $invoiceNumber,
                'provider_id' => $data['provider_id'] ?? '',
                'policy_number' => $invoiceNumber,
                'issue_date' => $data['issue_date'],
                'last_expiry_date' => $data['last_expiry_date'],
                'expiry_date' => $expiryDate,
                'status' => $data['status'],
                'remarks' => $data['remarks'] ?? null,
            ]);

            Renewal::create([
                'vehicle_id' => $data['vehicle_id'],
                'renewal_type_id' => $renewalType->id,
                'renewable_type' => $modelClass,
                'renewable_id' => $renewable->id,
                'status' => $data['status'],
                'start_date' => $data['last_expiry_date'],
                'expiry_date' => $expiryDate,
                'reminder_date' => Carbon::parse($expiryDate)->subDays(7),
                'remarks' => $data['remarks'] ?? null,
            ]);

            DB::commit();

            return $renewable;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function resolveRenewableModel1(string $type)
    {
        // dd($type);
        $renewalType = RenewalType::where('slug', $type)->first();
        // dd($renewalType);

        $model_class = "App\\Models\\" . Str::studly($renewalType->slug);

        if (!class_exists($model_class)) {
            throw new \Exception("Model {$model_class} does not exist.");
        }

        return $model_class;

        // return match ($type) {
        //     'bluebook' => \App\Models\Bluebook::class,
        //     'pollution' => \App\Models\PollutionCheck::class,
        //     'vehicle-tax' => \App\Models\VehicleTax::class,
        //     default => null,
        // };
    }

    private function calculateExpiryDate1($lastExpiryDate)
    {
        $engDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($lastExpiryDate, '-');

        $engExpiryDate = Carbon::parse($engDate)
            ->addDays(365)
            ->format('Y-m-d');

        $nepExpiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($engExpiryDate, '-');

        $parts = explode('-', $nepExpiryDate);

        return sprintf('%04d-%02d-%02d', $parts[0], $parts[1], $parts[2]);
    }

    public function updateRenewal2(array $data)
    {
        // dd($data);
        DB::beginTransaction();

        try {

            $vehicle = $data['vehicle'];
            $type = $data['type'];

            // Get Renewal Type once
            $renewalType = RenewalType::where('slug', $type)->firstOrFail();

            // Resolve model dynamically
            $modelClass = $this->resolveRenewableModel($renewalType);

            // Calculate expiry dynamically
            $expiryDates = $this->calculateExpiryDate(
                $data['expiry_date_bs'],
                $renewalType,
                $vehicle
            );

            $invoiceNumber = AppHelper::generateInvoiceNumber($type);

            // Create Renewable Record
            $renewable = $modelClass::create([
                'vehicle_id' => $vehicle->id,
                'invoice_no' => $invoiceNumber,
                'issue_date_bs' => $data['issue_date_bs'],
                'issue_date_ad' => $expiryDates['issue_ad'],

                'expiry_date_bs' => $data['expiry_date_bs'],
                'expiry_date_ad' => $expiryDates['start_ad'],

                'renewed_expiry_date_bs' => $expiryDates['expiry_bs'],
                'renewed_expiry_date_ad' => $expiryDates['expiry_ad'],

                'payment_status' => $data['payment_status'],
                'remarks' => $data['remarks'],

                'provider_id' => $data['provider_id'] ?? null,
                'policy_number' => $data['policy_number'] ?? null,
                'insurance_type' => $data['insurance_type'] ?? null
            ]);

            // Create Renewal Tracking Record
            Renewal::create([
                'renewal_type_id' => $renewalType->id,
                'renewable_type' => $modelClass,
                'renewable_id' => $renewable->id,

                'status' => 'valid',

                'start_date_bs' => $expiryDates['start_bs'] ?? null,
                'start_date_ad' => $expiryDates['start_ad'] ?? null,

                'expiry_date_bs' => $expiryDates['expiry_bs'],
                'expiry_date_ad' => $expiryDates['expiry_ad'],

                'reminder_date' => Carbon::parse($expiryDates['expiry_ad'])->subDays(7),

                'remarks' => $data['remarks'],
                'is_paid' => $data['payment_status'] === 'paid',
            ]);


            DB::commit();

            return $renewable;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function resolveRenewableModel2($renewalType)
    {
        $modelClass = "App\\Models\\" . \Str::studly($renewalType->slug);

        if (!class_exists($modelClass)) {
            throw new \Exception("Model {$modelClass} does not exist.");
        }

        return $modelClass;
    }

    private function calculateExpiryDate2($startDateBs, $renewalType, $vehicle)
    {
        // dd($startDateBs);
        // Convert BS → AD
        $startAd = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($startDateBs, '-');

        $date = Carbon::parse($startAd);
        // dd($date);

        // Get validity based on vehicle type
        $validity = $renewalType->getValidityForVehicle($vehicle);
        // dd($validity);

        if ($validity['value'] && $validity['unit']) {
            $date->add($validity['unit'], $validity['value'])->subDay();
        }

        $expiryAd = $date->format('Y-m-d');

        // Convert AD → BS
        $expiryBs = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($expiryAd, '-');
        // dd($expiryBs);

        return [
            'start_ad' => $startAd,
            'issue_ad' => $startAd,
            'expiry_ad' => $expiryAd,
            'expiry_bs' => $expiryBs,
        ];
    }

    /**
     * Update Renewal dynamically
     */
    public function updateRenewal(array $data)
    {
        DB::beginTransaction();

        try {
            $vehicle = $data['vehicle'];
            $type = strtolower(str_replace('_', '-', $data['type']));

            // Get Renewal Type
            $renewalType = RenewalType::where('slug', $type)->firstOrFail();

            // Resolve dynamic model (Bluebook, Insurance, etc.)
            $modelClass = $this->resolveRenewableModel($renewalType);

            // Calculate expiry dates
            $expiryDates = $this->calculateExpiryDate(
                $data['expiry_date_bs'] ?? null,
                $renewalType,
                $vehicle
            );

            /**
             * ----------------------------------
             * Step 1: Find or Restore Main Record
             * ----------------------------------
             */
            $renewable = $modelClass::withTrashed()
                ->where('vehicle_id', $vehicle->id)
                ->latest('id')
                ->first();

            if ($renewable && method_exists($renewable, 'trashed') && $renewable->trashed()) {
                $renewable->restore();
            }

            // Generate invoice only if new
            $invoiceNumber = $renewable
                ? $renewable->invoice_no
                : AppHelper::generateInvoiceNumber($type);

            // Prepare main table data
            $renewableData = [
                'vehicle_id' => $vehicle->id,
                'invoice_no' => $invoiceNumber,
                'issue_date_bs' => $data['issue_date_bs'] ?? null,
                'issue_date_ad' => $expiryDates['start_ad'] ?? null,
                'expiry_date_bs' => $data['expiry_date_bs'] ?? null,
                'expiry_date_ad' => $expiryDates['expiry_ad'] ?? null,
                'renewed_expiry_date_bs' => $expiryDates['expiry_bs'] ?? null,
                'renewed_expiry_date_ad' => $expiryDates['expiry_ad'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'remarks' => $data['remarks'] ?? null,
            ];

            foreach (['provider_id', 'policy_number', 'insurance_type'] as $field) {
                if (array_key_exists($field, $data)) {
                    $renewableData[$field] = $data[$field];
                }
            }

            // Update or create main record
            if ($renewable) {
                $renewable->update($renewableData);
            } else {
                $renewable = $modelClass::create($renewableData);
            }

            /**
             * ----------------------------------
             * Step 2: Find existing Renewal record (single history)
             * ----------------------------------
             */
            $renewal = $renewalType->renewals()
                ->where('vehicle_id', $vehicle->id)
                ->latest('id')
                ->first();

            $renewalData = [
                'vehicle_id' => $vehicle->id,
                'renewal_type_id' => $renewalType->id,
                'renewable_type' => $modelClass,
                'renewable_id' => $renewable->id,
                'start_date_bs' => $expiryDates['start_bs'] ?? null,
                'start_date_ad' => $expiryDates['start_ad'] ?? null,
                'expiry_date_bs' => $expiryDates['expiry_bs'] ?? null,
                'expiry_date_ad' => $expiryDates['expiry_ad'] ?? null,
                'status' => 'renewed',
                'is_paid' => ($data['payment_status'] ?? 'unpaid') === 'paid',
                'remarks' => $data['remarks'] ?? null,
            ];

            // Update or create the single renewal history
            if ($renewal) {
                $renewal->update($renewalData);
            } else {
                $renewalType->renewals()->create($renewalData);
            }

            DB::commit();

            return $renewable;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRenewal12(array $data)
    {
        DB::beginTransaction();

        try {
            $vehicle = $data['vehicle'];
            $type = $data['type'];

            $renewalType = RenewalType::where('slug', $type)->firstOrFail();

            $modelClass = $this->resolveRenewableModel($renewalType);

            $expiryDates = $this->calculateExpiryDate(
                $data['expiry_date_bs'] ?? null,
                $renewalType,
                $vehicle
            );

            $invoiceNumber = AppHelper::generateInvoiceNumber($type);

            $renewableData = [
                'vehicle_id' => $vehicle->id,
                'invoice_no' => $invoiceNumber,
                'issue_date_bs' => $data['issue_date_bs'] ?? null,
                'issue_date_ad' => $expiryDates['start_ad'] ?? null,
                'expiry_date_bs' => $data['expiry_date_bs'] ?? null,
                'expiry_date_ad' => $expiryDates['start_ad'] ?? null,
                'renewed_expiry_date_bs' => $expiryDates['expiry_bs'] ?? null,
                'renewed_expiry_date_ad' => $expiryDates['expiry_ad'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'remarks' => $data['remarks'] ?? null,
            ];

            // Add dynamic fields if exist (insurance)
            if (isset($data['provider_id']))
                $renewableData['provider_id'] = $data['provider_id'];
            if (isset($data['policy_number']))
                $renewableData['policy_number'] = $data['policy_number'];
            if (isset($data['insurance_type']))
                $renewableData['insurance_type'] = $data['insurance_type'];

            // Create or update renewal record
            $renewable = $modelClass::create($renewableData);

            // Track renewal
            $renewalType->renewals()->create([
                'vehicle_id' => $vehicle->id,
                'renewable_type' => $modelClass,
                'renewable_id' => $renewable->id,
                'status' => 'renewed',
                'start_date_bs' => $expiryDates['start_bs'] ?? null,
                'start_date_ad' => $expiryDates['start_ad'] ?? null,
                'expiry_date_bs' => $expiryDates['expiry_bs'] ?? null,
                'expiry_date_ad' => $expiryDates['expiry_ad'] ?? null,
                'reminder_date' => isset($expiryDates['expiry_ad']) ? Carbon::parse($expiryDates['expiry_ad'])->subDays(7) : null,
                'remarks' => $data['remarks'] ?? null,
                'is_paid' => $data['payment_status'] === 'paid',
            ]);

            DB::commit();
            return $renewable;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Resolve dynamic model class
     */
    private function resolveRenewableModel($renewalType)
    {
        $modelClass = "App\\Models\\" . \Str::studly($renewalType->slug);
        if (!class_exists($modelClass)) {
            throw new \Exception("Model {$modelClass} does not exist.");
        }
        return $modelClass;
    }

    /**
     * Calculate expiry dynamically
     */
    private function calculateExpiryDate($startDateBs, $renewalType, $vehicle)
    {
        if (!$startDateBs)
            return [];

        $startAd = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($startDateBs, '-');
        $date = Carbon::parse($startAd);

        $validity = $renewalType->getValidityForVehicle($vehicle);

        if (!empty($validity['value']) && !empty($validity['unit'])) {
            $date->add($validity['unit'], $validity['value'])->subDay();
        }

        $expiryAd = $date->format('Y-m-d');
        $expiryBs = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($expiryAd, '-');

        return [
            'start_ad' => $startAd,
            'issue_ad' => $startAd,
            'expiry_ad' => $expiryAd,
            'start_bs' => $startDateBs,
            'expiry_bs' => $expiryBs,
        ];
    }

    /**
     * Generate $renewalFields dynamically from DB
     */
    public function generateRenewalFields($insuranceProviders)
    {
        $fields = [];

        $renewalTypes = RenewalType::all();

        foreach ($renewalTypes as $type) {

            $slug = $type->slug;

            $fields[$slug] = [];

            // Special fields dynamically for insurance
            if ($slug === 'insurance') {
                $fields[$slug][] = [
                    'name' => 'provider_id',
                    'label' => 'Insurance Provider',
                    'type' => 'select',
                    'options' => $insuranceProviders->pluck('name', 'id')->toArray()
                ];
                $fields[$slug][] = [
                    'name' => 'insurance_type',
                    'label' => 'Insurance Type',
                    'type' => 'select',
                    'options' => ['general' => 'General', 'third' => 'Third', 'partial' => 'Partial']
                ];
                // $fields[$slug][] = [
                //     'name' => 'issue_date_bs',
                //     'label' => 'Issue Date',
                //     'type' => 'date'
                // ];

                $fields[$slug][] = [
                    'name' => 'policy_number',
                    'label' => 'Policy Number',
                    'type' => 'text'
                ];
            }

            // Common fields for all renewal types
            $fields[$slug][] = [
                'name' => 'expiry_date_bs',
                'label' => 'Expiry Date',
                'type' => 'date'
            ];
            $fields[$slug][] = [
                'name' => 'payment_status',
                'label' => 'Payment Status',
                'type' => 'select',
                'options' => ['paid' => 'Paid', 'unpaid' => 'Unpaid']
            ];
            $fields[$slug][] = [
                'name' => 'remarks',
                'label' => 'Remarks',
                'type' => 'text'
            ];
        }

        return $fields;
    }
}