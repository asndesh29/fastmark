<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Models\Customer;
use App\Helpers\AppHelper;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerService
{
    // public function list(Request $request, $perPage = null)
    // {
    //     $keywords = explode(' ', $request->search ?? '');
    //     $perPage = $perPage ?? config('default_pagination', 10);

    //     return Customer::when($request->search, function ($query) use ($keywords) {
    //         foreach ($keywords as $word) {
    //             $query->orWhere('name', 'like', "%{$word}%");
    //         }
    //     })->orderBy('created_at', 'desc')->paginate($perPage);
    // }

    public function list(Request $request, $perPage = null)
    {
        $perPage = $perPage ?? config('default_pagination', 10);

        $query = Customer::query();

        // Customer name search
        if ($request->customer) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->customer . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->customer . '%')
                    ->orWhere('last_name', 'like', '%' . $request->customer . '%');
            });
        }

        // Email filter
        if ($request->email) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Phone filter
        if ($request->phone) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        // Status filter
        if ($request->status != 'all' && $request->status !== null) {
            $query->where('is_active', $request->status);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    public function store($data)
    {
        // dd($data);
        // if (isset($data['image'])) {
        //     $data['image'] = AppHelper::upload('customer', 'png', $data['image']);
        // }

        $customer = Customer::create($data);

        // Store multiple vehicles & create renewal entry for each
        foreach ($data['vehicle_types'] as $index => $vehicleTypeId) {
            Vehicle::create([
                'customer_id' => $customer->id,
                'vehicle_type_id' => $vehicleTypeId,
                'vehicle_category_id' => $data['vehicle_categories'][$index],
                'registration_no' => $data['registration_no'][$index],
                'permit_no' => $data['permit_no'][$index] ?? null,
                'chassis_no' => $data['chassis_no'][$index] ?? null,
                'engine_no' => $data['engine_no'][$index] ?? null,
                'engine_cc' => $data['engine_cc'][$index] ?? null,
                'capacity' => $data['capacity'][$index] ?? null,
            ]);
        }
        return $customer;
    }

    public function getById($id)
    {
        return Customer::findOrFail($id);
    }

    public function update(Customer $customer, $data)
    {
        if (isset($data['image'])) {
            $data['image'] = AppHelper::update('customer', $customer->image, 'png', $data['image']);
        }

        return $customer->update($data);
    }

    public function destroy($id)
    {
        $customer = $this->getById($id);

        if (!$customer) {
            return false;
        }

        return $customer->delete();
    }
}