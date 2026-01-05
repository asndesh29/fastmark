<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;

use App\Helpers\AppHelper;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\RoadPermit;
use App\Models\Vehicle;
use App\Models\VehicleTax;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VehicleTaxService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType', 'vehicleTax.renewal'])
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
                $query->whereHas('vehicleTax', function ($q) use ($invoice) {
                    $q->where('invoice_no', 'like', "%{$invoice}%");
                });
            })
            ->when($request->last_expiry_date, function ($query, $date) {
                $query->whereHas('vehicleTax', function ($q) use ($date) {
                    $q->whereDate('last_expiry_date', $date);
                });
            })
            ->when($request->new_expiry_date, function ($query, $date) {
                $query->whereHas('vehicleTax', function ($q) use ($date) {
                    $q->whereDate('expiry_date', $date);
                });
            })
            ->when($request->status && $request->status !== 'all', function ($query, $status) {
                $query->whereHas('vehicleTax.renewal', function ($q) use ($status) {
                    $q->where('status', strtolower($status));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $vehicles;
    }


    public function store(array $data)
    {
        // dd($data);
        $renewal_type = RenewalType::where('slug', $data['type'])->first();

        if (!$renewal_type) {
            throw new \Exception("Renewal type '{$data['type']}' not found.");
        }

        // Generate the invoice number automatically
        $invoice_no = AppHelper::generateInvoiceNumber('tax');

        $expiryDate = $this->calculateExpiryDate($data['last_expiry_date']);

        $roadPermit = VehicleTax::create([
            'vehicle_id' => $data['vehicle_id'],
            'invoice_no' => $invoice_no,
            'issue_date' => $data['issue_date'],
            'last_expiry_date' => $data['last_expiry_date'],
            'tax_amount' => $data['tax_amount'],
            'renewal_charge' => $data['renewal_charge'],
            'income_tax' => $data['income_tax'],
            'expiry_date' => $expiryDate,
            'status' => $data['status'],
            'remarks' => $data['remarks'],
        ]);

        Renewal::create([
            'vehicle_id' => $data['vehicle_id'],
            'renewal_type_id' => $renewal_type->id,
            'renewable_type' => VehicleTax::class,
            'renewable_id' => $roadPermit->id,
            'status' => $data['status'],
            'start_date' => $data['last_expiry_date'],
            'expiry_date' => $expiryDate,
            'reminder_date' => Carbon::parse($expiryDate)->subDays(7),
            'remarks' => $data['remarks'] ?? null,
        ]);

        return $roadPermit;

    }

    public function getById($id)
    {
        return VehicleTax::findOrFail($id);
    }

    public function update(VehicleTax $vehicletax, array $data)
    {
        // Find the renewal type
        $renewalType = RenewalType::where('slug', $data['type'])->firstOrFail();

        $expiryDate = $this->calculateExpiryDate($data['last_expiry_date']);

        // Update the Bluebook
        $vehicletax->update([
            'vehicle_id' => $data['vehicle_id'],
            'issue_date' => $data['issue_date'],
            'last_expiry_date' => $data['last_expiry_date'],
            'tax_amount' => $data['tax_amount'],
            'renewal_charge' => $data['renewal_charge'],
            'income_tax' => $data['income_tax'],
            'expiry_date' => $expiryDate,
            'status' => $data['status'],
            'remarks' => $data['remarks'],
        ]);

        // Update or create the related Renewal
        Renewal::updateOrCreate(
            [
                'renewable_id' => $vehicletax->id,
                'renewable_type' => VehicleTax::class,
            ],
            [
                'vehicle_id' => $data['vehicle_id'],
                'renewal_type_id' => $renewalType->id,
                'status' => $data['status'],
                'start_date' => $data['issue_date'],
                'expiry_date' => $expiryDate,
                'reminder_date' => Carbon::parse($expiryDate)->subDays(7),
                'remarks' => $data['remarks'] ?? null,
            ]
        );

        return $vehicletax->fresh();
    }

    public function delete($id)
    {
        $vehicletax = $this->getById($id);

        if (!$vehicletax) {
            return false;
        }

        return $vehicletax->delete();
    }

    private function calculateExpiryDate($lastExpiryDate)
    {
        // Convert Nepali issue date to English (Y-m-d)
        $engIssueDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($lastExpiryDate, '-');

        // Add days based on category
        $engExpiryDate = Carbon::parse($engIssueDate)->addDays(364)->format('Y-m-d');

        // Convert back to Nepali date
        $nepExpiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($engExpiryDate, '-');

        // Ensure format YYYY-MM-DD
        $parts = explode('-', $nepExpiryDate);
        $nepExpiryDate = sprintf('%04d-%02d-%02d', $parts[0], $parts[1], $parts[2]);

        return $nepExpiryDate;
    }
}