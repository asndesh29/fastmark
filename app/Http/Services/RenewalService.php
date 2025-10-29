<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Models\Bluebook;
use App\Models\Customer;
use App\Models\Renewal;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\RoadPermit;
use App\Models\PollutionCheck;
use App\Models\VehiclePass;
use App\Models\Insurance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RenewalService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType', 'Renewals'])
        ->when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);

        return $vehicles;
    }

    public function store1($data)
    {
        // Create customer
        $customer = Customer::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        // Store multiple vehicles & create renewal entry for each
        foreach ($data['vehicle_type'] as $index => $vehicleType) {
            $renewedDate = $data['renewed_date'][$index];
            // dd($renewedDate); // 2082-05-01

            $renewedDateEn = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($renewedDate, '-');
            $renewedDateEn = Carbon::parse($renewedDateEn); // make sure it's Carbon

            // Add 365 days
            $expiryDateEn = $renewedDateEn->copy()->addDays(365); // English date
            // dd($expiryDateEn);

            $expiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($expiryDateEn, '-');
            // dd($expiryDate);

            $vehicle_type = VehicleType::where('id', $vehicleType)->first();
            // dd($vehicle_type);

            $vehicle = Vehicle::create([
                'customer_id' => $customer->id,
                'vehicle_type_id' => $vehicle_type->id,
                'registration_no' => $data['registration_no'][$index],
                'chassis_no' => $data['chassis_no'][$index],
                'engine_no' => $data['engine_no'][$index],
                'engine_cc' => $data['engine_cc'][$index],
                'last_renewed_at' => $renewedDate,
                'expiry_date' => $expiryDate
            ]);

            // create a renewal record linked to this vehicle & customer
            Renewal::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
            ]);
        }

        return $customer;
    }

    public function store(array $data)
    {
        // dd($data);
        DB::beginTransaction();

        try {
            // dd(1);
            $engIssueDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($data['issue_date'], '-');
            // dd($engIssueDate);

            $engExpiryDate = Carbon::parse($engIssueDate)->addDays(365)->format('Y-m-d');

            $nepExpiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($engExpiryDate, '-');
            // dd($nepExpiryDate);

            // Ensure zero-padded month/day format
            $parts = explode('-', $nepExpiryDate);
            $nepExpiryDate = sprintf('%04d-%02d-%02d', $parts[0], $parts[1], $parts[2]);

            $data['expiry_date'] = $nepExpiryDate;

            // Step 1: Create the specific renewable record
            switch ($data['type']) {
                case 'bluebook':
                $renewable = Bluebook::create([
                    'vehicle_id' => $data['vehicle_id'],
                    'book_number' => $data['book_number'],
                    'issue_date' => $data['issue_date'],
                    'last_renewed_at' => $data['last_renewed_at'] ?? null,
                    'expiry_date' => $data['expiry_date'],
                    'status' => $data['status'] ?? 'pending',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                break;

            case 'road_permit':
                $renewable = RoadPermit::create([
                    'vehicle_id' => $data['vehicle_id'],
                    'permit_number' => $data['permit_number'],
                    'issue_date' => $data['issue_date'],
                    'expiry_date' => $data['expiry_date'],
                    'status' => $data['status'] ?? 'pending',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                break;

            case 'pollution':
                $renewable = PollutionCheck::create([
                    'vehicle_id' => $data['vehicle_id'],
                    'certificate_number' => $data['certificate_number'],
                    'check_date' => $data['check_date'],
                    'issue_date' => $data['issue_date'],
                    'expiry_date' => $data['expiry_date'],
                    'status' => $data['status'] ?? 'pending',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                break;

            case 'check_pass':
                $renewable = VehiclePass::create([
                    'vehicle_id' => $data['vehicle_id'],
                    'issue_date' => $data['issue_date'],
                    'expiry_date' => $data['expiry_date'],
                    'inspection_result' => $data['inspection_result'],
                    // 'status' => $data['status'] ?? 'pending',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                break;

             case 'insurance':
                $renewable = Insurance::create([
                    'vehicle_id' => $data['vehicle_id'],
                    'provider_id' => $data['provider_id'],
                    'policy_number' => $data['policy_number'],
                    'issue_date' => $data['issue_date'],
                    'expiry_date' => $data['expiry_date'],
                    'amount' => $data['amount'],
                    // 'status' => $data['status'] ?? 'pending',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                break;

                default:
                    throw new \Exception("Invalid renewal type.");
            }

            // Step 2: Create the polymorphic renewal
            Renewal::create([
                'vehicle_id' => $data['vehicle_id'],
                'renewal_type_id' => $data['renewal_type_id'],
                'renewable_type' => get_class($renewable),
                'renewable_id' => $renewable->id,
                'status' => $data['status'] ?? 'pending',
                'start_date' => $data['issue_date'],
                'expiry_date' => $data['expiry_date'],
                'reminder_date' => now()->addDays(7),
                'remarks' => $data['remarks'] ?? null,
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; // re-throw so controller can handle it
        }
    }

    public function getById($id)
    {
        return Renewal::findOrFail($id);
    }

    public function update(Renewal $renewal, $data)
    {
        return $renewal->update($data);
    }

    public function delete($id)
    {
        $renewal = $this->getById($id);

        if (!$renewal) {
            return false;
        }

        return $renewal->delete();
    }
}