<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Helpers\AppHelper;
use App\Models\Bluebook;
use App\Models\Customer;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BluebookService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $vehicles = Vehicle::with(['owner', 'vehicleCategory', 'vehicleType', 'bluebook.renewal'])
            ->when($request->customer, function ($query, $customer) {
                $query->whereHas('owner', function ($q) use ($customer) {
                    $q->where('first_name', 'like', "%{$customer}%")
                        ->orWhere('last_name', 'like', "%{$customer}%");
                });
            })
            ->when($request->registration_no, function ($query, $registration_no) {
                $query->where('registration_no', 'like', "%{$registration_no}%");
            })
            ->when($request->last_expiry_date, function ($query, $date) {
                $query->whereHas('bluebook', function ($q) use ($date) {
                    $q->whereDate('last_expiry_date', $date);
                });
            })
            ->when($request->new_expiry_date, function ($query, $date) {
                $query->whereHas('bluebook', function ($q) use ($date) {
                    $q->whereDate('expiry_date', $date);
                });
            })
            ->when($request->status && $request->status !== 'all', function ($query, $status) {
                $query->whereHas('bluebook.renewal', function ($q) use ($status) {
                    $q->where('status', strtolower($status));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Append filters to the pagination links
        // $vehicles->appends($request->all());

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

        // Generate the book number automatically
        $book_number = AppHelper::generateInvoiceNumber('bluebook');
        // dd($book_number);

        $expiryDate = $this->calculateExpiryDate($data['last_expiry_date']);
        // dd($expiryDate);

        $bluebook = Bluebook::create([
            'vehicle_id' => $data['vehicle_id'],
            'book_number' => $book_number,
            'issue_date' => $data['issue_date'],
            'last_expiry_date' => $data['last_expiry_date'],
            'expiry_date' => $expiryDate,
            'status' => $data['status'],
            'remarks' => $data['remarks'],
        ]);

        Renewal::create([
            'vehicle_id' => $data['vehicle_id'],
            'renewal_type_id' => $renewal_type->id,
            'renewable_type' => Bluebook::class,
            'renewable_id' => $bluebook->id,
            'status' => $data['status'],
            'start_date' => $data['last_expiry_date'],
            'expiry_date' => $expiryDate,
            'reminder_date' => Carbon::parse($expiryDate)->subDays(7),
            'remarks' => $data['remarks'] ?? null,
        ]);

        return $bluebook;
    }

    private function calculateExpiryDate($lastExpiryDate)
    {
        $engIssueDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep($lastExpiryDate, '-');
        // dd($engIssueDate);

        $engExpiryDate = Carbon::parse($engIssueDate)->addDays(364)->format('Y-m-d');

        $nepExpiryDate = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep($engExpiryDate, '-');
        // dd($nepExpiryDate);

        $parts = explode('-', $nepExpiryDate);
        $nepExpiryDate = sprintf('%04d-%02d-%02d', $parts[0], $parts[1], $parts[2]);

        return $nepExpiryDate;
    }


    public function getById($id)
    {
        return Bluebook::findOrFail($id);
    }

    public function update(Bluebook $bluebook, array $data)
    {
        // Find the renewal type
        $renewalType = RenewalType::where('slug', $data['type'])->firstOrFail();

        $expiryDate = $this->calculateExpiryDate($data['last_expiry_date']);

        // Update the Bluebook
        $bluebook->update([
            'vehicle_id' => $data['vehicle_id'],
            'issue_date' => $data['issue_date'],
            'last_expiry_date' => $data['last_expiry_date'],
            'expiry_date' => $expiryDate,
            'status' => $data['status'],
            'remarks' => $data['remarks'],
        ]);

        // Update or create the related Renewal
        Renewal::updateOrCreate(
            [
                'renewable_id' => $bluebook->id,
                'renewable_type' => Bluebook::class,
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

        return $bluebook->fresh();
    }

    public function delete($id)
    {
        $bluebook = $this->getById($id);

        if (!$bluebook) {
            return false;
        }

        return $bluebook->delete();
    }
}