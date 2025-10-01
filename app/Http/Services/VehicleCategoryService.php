<?php

namespace App\Http\Services;

use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class VehicleCategoryService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        return VehicleCategory::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function store($data)
    {
        return VehicleCategory::create($data);
    }

    public function getById($id)
    {
        return VehicleCategory::findOrFail($id);
    }

    public function update(VehicleCategory $vehicleCategory, $data)
    {
        return $vehicleCategory->update($data);
    }

    public function delete($id)
    {
        $vehicleCategory = $this->getById($id);

        if (!$vehicleCategory) {
            return false;
        }

        return $vehicleCategory->delete();
    }
}