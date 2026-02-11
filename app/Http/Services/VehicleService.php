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

class VehicleService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType'])
            ->when($request->customer, function ($query, $customer) {
                $query->whereHas('owner', function ($q) use ($customer) {
                    $q->where('first_name', 'like', "%{$customer}%")
                        ->orWhere('last_name', 'like', "%{$customer}%");
                });
            })
            ->when($request->registration_no, function ($query, $registration_no) {
                $query->where('registration_no', 'like', "%{$registration_no}%");
            })
            // ->when($request->status && $request->status !== 'all', function ($query, $status) {
            //     if (in_array($status, ['active', 'inactive'])) {
            //         $query->where('is_active', $status === 'active' ? 1 : 0);
            //     }
            // })
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->where('is_active', (int) $request->status);
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

    public function updateRenewal(array $data)
    {
        // dd($data);
        $renewalType = RenewalType::where('slug', $data['type'])->first();

        if (!$renewalType) {
            throw new \Exception("Renewal type '{$data['type']}' not found.");
        }

        DB::beginTransaction();

        try {

            $modelClass = $this->resolveRenewableModel($data['type']);

            if (!$modelClass) {
                throw new \Exception("Invalid renewal model for type '{$data['type']}'.");
            }

            $invoiceNumber = AppHelper::generateInvoiceNumber($data['type']);

            $expiryDate = $this->calculateExpiryDate($data['last_expiry_date']);

            $renewable = $modelClass::create([
                'vehicle_id' => $data['vehicle_id'],
                'invoice_no' => $invoiceNumber,
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

    private function resolveRenewableModel(string $type)
    {
        return match ($type) {
            'bluebook' => \App\Models\Bluebook::class,
            'pollution' => \App\Models\PollutionCheck::class,
            'vehicle-tax' => \App\Models\VehicleTax::class,
            default => null,
        };
    }

    private function calculateExpiryDate($lastExpiryDate)
    {
        $engDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($lastExpiryDate, '-');

        $engExpiryDate = Carbon::parse($engDate)
            ->addDays(365)
            ->format('Y-m-d');

        $nepExpiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($engExpiryDate, '-');

        $parts = explode('-', $nepExpiryDate);

        return sprintf('%04d-%02d-%02d', $parts[0], $parts[1], $parts[2]);
    }
}