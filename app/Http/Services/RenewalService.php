<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Models\Customer;
use App\Models\Renewal;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RenewalService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        return Renewal::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function store($data)
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

    public function getById($id)
    {
        return Renewal::findOrFail($id);
    }

    public function show()
    {

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