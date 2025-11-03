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

        return Vehicle::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);
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