<?php

namespace App\Http\Services;

use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        return VehicleType::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function store($data)
    {
        return VehicleType::create($data);
    }

    public function getById($id)
    {
        return VehicleType::findOrFail($id);
    }

    public function update(VehicleType $vehicleType, $data)
    {
        return $vehicleType->update($data);
    }

    public function delete($id)
    {
        $vehicleType = $this->getById($id);

        if (!$vehicleType) {
            return false;
        }

        return $vehicleType->delete();
    }
}