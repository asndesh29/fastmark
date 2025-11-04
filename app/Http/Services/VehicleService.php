<?php

namespace App\Http\Services;

use App\Models\Vehicle;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;

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
        return Vehicle::store($data);
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
}