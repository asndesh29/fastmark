<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;

use App\Helpers\AppHelper;
use App\Models\Insurance;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsuranceService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType', 'insurance.renewal'])
            ->when($request->customer, function ($query, $customer) {
                $query->whereHas('owner', function ($q) use ($customer) {
                    $q->where('first_name', 'like', "%{$customer}%")
                        ->orWhere('last_name', 'like', "%{$customer}%");
                });
            })
            ->when($request->registration_no, function ($query, $registration_no) {
                $query->where('registration_no', 'like', "%{$registration_no}%");
            })
            ->when($request->invoice, function ($query, $invoice) {
                $query->whereHas('insurance', function ($q) use ($invoice) {
                    $q->where('invoice_no', 'like', "%{$invoice}%");
                });
            })
            ->when($request->last_expiry_date, function ($query, $date) {
                $query->whereHas('insurance', function ($q) use ($date) {
                    $q->whereDate('last_expiry_date', $date);
                });
            })
            ->when($request->new_expiry_date, function ($query, $date) {
                $query->whereHas('insurance', function ($q) use ($date) {
                    $q->whereDate('expiry_date', $date);
                });
            })
            ->when($request->status && $request->status !== 'all', function ($query, $status) {
                $query->whereHas('insurance.renewal', function ($q) use ($status) {
                    $q->where('status', strtolower($status));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $vehicles;
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            // Get renewal type
            $renewalType = RenewalType::where('slug', $data['renewable_type'])
                ->firstOrFail();

            // Get vehicle
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);

            // Generate book number
            $invoiceNumber = AppHelper::generateInvoiceNumber('insurance');

            // Calculate expiry using dynamic validity
            $expiryData = $this->calculateExpiryDate(
                $data['expiry_date_bs'],
                $renewalType,
                $vehicle
            );
            // dd($expiryData);

            // Create Vehicle Pass
            $vehiclePass = Insurance::create([
                'vehicle_id' => $vehicle->id,
                'invoice_no' => $invoiceNumber,
                'expiry_date_bs' => $data['expiry_date_bs'],
                'renewed_expiry_date_bs' => $expiryData['expiry_bs'],
                'renewed_expiry_date_ad' => $expiryData['expiry_ad'],
                'payment_status' => $data['payment_status'],
                'remarks' => $data['remarks'] ?? null,
            ]);

            // Create Renewal using relationship (cleaner)
            $vehiclePass->renewals()->create([
                'vehicle_id' => $vehicle->id,
                'renewal_type_id' => $renewalType->id,
                'status' => 'renewed',
                'is_paid' => $data['payment_status'] === 'paid' ? 1 : 0,
                'start_date_bs' => $expiryData['start_bs'],
                'expiry_date_bs' => $expiryData['expiry_bs'],
                'start_date_ad' => $expiryData['start_ad'],
                'expiry_date_ad' => $expiryData['expiry_ad'],
                'reminder_date' => Carbon::parse($expiryData['expiry_ad'])->subDays(7),
                'remarks' => $data['remarks'] ?? null,
            ]);

            DB::commit();

            return $vehiclePass;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getById($id)
    {
        return Insurance::findOrFail($id);
    }

    public function update(Insurance $insurance, array $data)
    {
        // dd($data);
        DB::beginTransaction();

        try {

            $renewalType = RenewalType::where('slug', $data['renewable_type'])
                ->firstOrFail();

            $vehicle = $insurance->vehicle;

            // Calculate expiry using dynamic validity
            $expiryData = $this->calculateExpiryDate(
                $data['expiry_date_bs'],
                $renewalType,
                $vehicle
            );

            // Update Bluebook
            $insurance->update([
                'expiry_date_bs' => $data['expiry_date_bs'],
                'renewed_expiry_date_bs' => $expiryData['expiry_bs'],
                'renewed_expiry_date_ad' => $expiryData['expiry_ad'],
                'payment_status' => $data['payment_status'],
                'remarks' => $data['remarks'] ?? null,
            ]);

            // Create Renewal History Record
            $insurance->renewals()->create([
                'vehicle_id' => $vehicle->id,
                'renewal_type_id' => $renewalType->id,
                'status' => 'renewed',
                'is_paid' => $data['payment_status'] === 'paid' ? 1 : 0,
                'start_date_bs' => $expiryData['start_bs'],
                'expiry_date_bs' => $expiryData['expiry_bs'],
                'start_date_ad' => $expiryData['start_ad'],
                'expiry_date_ad' => $expiryData['expiry_ad'],
                'reminder_date' => Carbon::parse($expiryData['expiry_ad'])->subDays(7),
                'remarks' => $data['remarks'] ?? null,
            ]);

            DB::commit();

            return $insurance->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $insurance = $this->getById($id);

        if (!$insurance) {
            return false;
        }

        return $insurance->delete();
    }

    private function calculateExpiryDate($startDateBs, $renewalType, $vehicle)
    {
        if (!$startDateBs) {
            return null;
        }

        $startAd = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep(
            $startDateBs,
            '-'
        );
        // dd($startAd);

        $date = Carbon::parse($startAd);
        // dd($date);

        $validity = $renewalType->getValidityForVehicle($vehicle);

        if (!empty($validity['value']) && !empty($validity['unit'])) {
            $date->add($validity['unit'], $validity['value'])->subDay();
        }

        $expiryAd = $date->format('Y-m-d');

        $expiryBs = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep(
            $expiryAd,
            '-'
        );

        return [
            'start_ad' => $startAd,
            'issue_ad' => $startAd,
            'expiry_ad' => $expiryAd,
            'start_bs' => $startDateBs,
            'expiry_bs' => $expiryBs,
        ];
    }
}