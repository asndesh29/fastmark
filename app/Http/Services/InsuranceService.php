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
        // dd($data);
        $renewal_type = RenewalType::where('slug', $data['type'])->first();
        // dd($renewal_type);

        if (!$renewal_type) {
            throw new \Exception("Renewal type '{$data['type']}' not found.");
        }

        // Generate the invoice number automatically
        $policy_number = AppHelper::generateInvoiceNumber('insurance');
        // dd($policy_number);

        $expiryDate = $this->calculateExpiryDate($data['issue_date']);
        // dd($expiryDate);

        $insurance = Insurance::create([
            'vehicle_id' => $data['vehicle_id'],
            'provider_id' => $data['provider_id'],
            'policy_number' => $policy_number,
            'issue_date' => $data['issue_date'],
            'amount' => $data['amount'],
            'expiry_date' => $expiryDate,
            'status' => $data['status'],
            'remarks' => $data['remarks'],
        ]);

        Renewal::create([
            'vehicle_id' => $data['vehicle_id'],
            'renewal_type_id' => $renewal_type->id,
            'renewable_type' => Insurance::class,
            'renewable_id' => $insurance->id,
            'status' => $data['status'],
            'start_date' => $data['issue_date'],
            'expiry_date' => $expiryDate,
            'reminder_date' => Carbon::parse($expiryDate)->subDays(7),
            'remarks' => $data['remarks'] ?? null,
        ]);

        return $insurance;

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

    public function getById($id)
    {
        return Insurance::findOrFail($id);
    }

    public function update(Insurance $insurance, array $data)
    {
        // dd($data);
        // Find the renewal type
        $renewalType = RenewalType::where('slug', $data['type'])->firstOrFail();

        $expiryDate = $this->calculateExpiryDate($data['issue_date']);
        // dd($expiryDate);

        // Update the Bluebook
        $insurance->update([
            'vehicle_id' => $data['vehicle_id'],
            'provider_id' => $data['provider_id'],
            'issue_date' => $data['issue_date'],
            'amount' => $data['amount'],
            'expiry_date' => $expiryDate,
            'status' => $data['status'],
            'remarks' => $data['remarks'],
        ]);

        // Update or create the related Renewal
        Renewal::updateOrCreate(
            [
                'renewable_id' => $insurance->id,
                'renewable_type' => Insurance::class,
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

        return $insurance->fresh();
    }

    public function delete($id)
    {
        $insurance = $this->getById($id);

        if (!$insurance) {
            return false;
        }

        return $insurance->delete();
    }
}