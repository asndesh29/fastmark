<?php

namespace App\Http\Controllers;

use App\Http\Services\VehicleCategoryService;
use App\Http\Services\VehicleTypeService;
use App\Models\VehicleCategory;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleCategoryController extends Controller
{
    protected $vehicleCategoryService;

    public function __construct(VehicleCategoryService $vehicleCategoryService)
    {
        $this->vehicleCategoryService = $vehicleCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $vehicleCategories = $this->vehicleCategoryService->list($request, $perPage);

        return view('vehicle.category.index', compact('vehicleCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->vehicleCategoryService->store($validated);

        return redirect()->route('admin.vehicle.category.index')->with('success', 'Vehicle Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleCategory $vehicleCategory)
    {
        $vehicleType = VehicleCategory::findOrFail($vehicleCategory->id);

        return view('vehicle.partials.index', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleCategory $category)
    {
        // dd($category);
        $vehicleCategory = $this->vehicleCategoryService->getById($category->id);

        return view('vehicle.category.edit', compact('vehicleCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleCategory $category)
    {
        $vehicle_type = $this->vehicleCategoryService->getById($category->id);

        $vaildated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->vehicleCategoryService->update($vehicle_type, $vaildated);

        return redirect()->route('admin.settings.vehicle.category.index')->with('success', 'Vehicle type updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function status(VehicleCategory $category, $status)
    {
        $category->is_active = in_array($status, [0, 1]) ? $status : 0;

        $category->save();

        return back()->with('success', 'Vehicle category status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleCategory $vehicleCategory)
    {
        $this->vehicleCategoryService->delete($vehicleCategory->id);

        return redirect()->route('admin.vehicle.category.index')->with('success', 'Vehicle category deleted successfully.');
    }
}
