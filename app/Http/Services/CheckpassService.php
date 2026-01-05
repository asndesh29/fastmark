<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;

use App\Helpers\AppHelper;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\RoadPermit;
use App\Models\Vehicle;
use App\Models\VehiclePass;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckpassService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType', 'vehiclePass.renewal'])
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
                $query->whereHas('vehiclePass', function ($q) use ($invoice) {
                    $q->where('invoice_no', 'like', "%{$invoice}%");
                });
            })
            ->when($request->last_expiry_date, function ($query, $date) {
                $query->whereHas('vehiclePass', function ($q) use ($date) {
                    $q->whereDate('last_expiry_date', $date);
                });
            })
            ->when($request->new_expiry_date, function ($query, $date) {
                $query->whereHas('vehiclePass', function ($q) use ($date) {
                    $q->whereDate('expiry_date', $date);
                });
            })
            ->when($request->status && $request->status !== 'all', function ($query, $status) {
                $query->whereHas('vehiclePass.renewal', function ($q) use ($status) {
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
        // dd($renewal_type);

        if (!$renewal_type) {
            throw new \Exception("Renewal type '{$data['type']}' not found.");
        }

        // Generate the invoice number automatically
        $invoice_no = AppHelper::generateInvoiceNumber('jachpass');
        // dd($invoice_no);

        $expiryDate = $this->calculateExpiryDate($data['last_expiry_date'], $data['vehicle_id']);
        // dd($expiryDate);

        $vehiclePass = VehiclePass::create([
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
        // dd($roadPermit);

        Renewal::create([
            'vehicle_id' => $data['vehicle_id'],
            'renewal_type_id' => $renewal_type->id,
            'renewable_type' => VehiclePass::class,
            'renewable_id' => $vehiclePass->id,
            'status' => $data['status'],
            'start_date' => $data['last_expiry_date'],
            'expiry_date' => $expiryDate,
            'reminder_date' => Carbon::parse($expiryDate)->subDays(7),
            'remarks' => $data['remarks'] ?? null,
        ]);

        return $vehiclePass;

    }

    private function calculateExpiryDate($lastExpiryDate, $vehicleId)
    {
        // Get the vehicle
        $vehicle = Vehicle::with('vehicleCategory')->findOrFail($vehicleId);

        // Convert Nepali issue date to English (Y-m-d)
        $engIssueDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($lastExpiryDate, '-');

        // Determine validity duration
        $categoryName = strtolower($vehicle->vehicleCategory->name ?? '');
        // dd($categoryName);

        if (in_array($categoryName, ['public', 'commercial'])) {
            // $daysToAdd = 181; // 6 months for public/commercial
            $engExpiryDate = Carbon::parse($engIssueDate)
                ->addMonths(6)
                ->subDay()
                ->format('Y-m-d');
            // dd($engExpiryDate);
        } else {
            // $daysToAdd = 365; // 1 year for private or others
            $engExpiryDate = Carbon::parse($engIssueDate)
                ->addYear()
                ->subDay()
                ->format('Y-m-d');
        }

        // Add days based on category
        // $engExpiryDate = Carbon::parse($engIssueDate)->addDays($daysToAdd)->format('Y-m-d');

        // Convert back to Nepali date
        $nepExpiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($engExpiryDate, '-');
        // dd($nepExpiryDate);

        $nepExpiryDate = Carbon::parse($nepExpiryDate)
            ->subDay()
            ->format('Y-m-d');
        // dd($nepExpiryDate);

        // Ensure format YYYY-MM-DD
        // $parts = explode('-', $nepExpiryDate);
        // $nepExpiryDate = sprintf('%04d-%02d-%02d', $parts[0], $parts[1], $parts[2]);

        return $nepExpiryDate;
    }



    public function getById($id)
    {
        return VehiclePass::findOrFail($id);
    }

    public function update(VehiclePass $checkpass, array $data)
    {
        // Find the renewal type
        $renewalType = RenewalType::where('slug', $data['type'])->firstOrFail();

        $expiryDate = $this->calculateExpiryDate($data['last_expiry_date'], $data['vehicle_id']);

        // Update the Bluebook
        $checkpass->update([
            'vehicle_id' => $data['vehicle_id'],
            'invoice_no' => $data['invoice_number'],
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
                'renewable_id' => $checkpass->id,
                'renewable_type' => VehiclePass::class,
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

        return $checkpass->fresh();
    }

    public function delete($id)
    {
        $vehiclePass = $this->getById($id);

        if (!$vehiclePass) {
            return false;
        }

        return $vehiclePass->delete();
    }
}