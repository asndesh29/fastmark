<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
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
        // Validate using the model's validateData method
        $validator = VehicleCategory::validateData($request->all());

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, store the data
        $validated = $validator->validated();

        $this->vehicleCategoryService->store($validated);

        AppHelper::success('Vehicle Category created successfully.');

        return redirect()->route('admin.settings.vehicle.category.index');
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
        // dd($request->all());
        $vehicle_category = $this->vehicleCategoryService->getById($category->id);
        // dd($vehicle_category);

        $validated = VehicleCategory::validateData($request->all(), $category);

        // If validation fails, redirect back with errors
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Get the validated data
        $validatedData = $validated->validated();

        // Update the vehicle type using the service
        $this->vehicleCategoryService->update($vehicle_category, $validatedData);

        // Show a success message
        AppHelper::success('Vehicle Category Updated Successfully.');

        // Redirect back to the vehicle type index page
        return redirect()->route('admin.settings.vehicle.category.index');
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
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleCategory $category)
    {
        $this->vehicleCategoryService->delete($category->id);

        AppHelper::success('Vehicle Category deleted successfully.');

        return redirect()->route('admin.settings.vehicle.category.index');
    }
}
