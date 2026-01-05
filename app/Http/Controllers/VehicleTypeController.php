<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Services\VehicleTypeService;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    protected $vehicleTypeService;

    public function __construct(VehicleTypeService $vehicleTypeService)
    {
        $this->vehicleTypeService = $vehicleTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $vehicleTypes = $this->vehicleTypeService->list($request, $perPage);

        return view('vehicle.type.index', compact('vehicleTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate using the model's validateData method
        $validator = VehicleType::validateData($request->all());

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, store the data
        $validated = $validator->validated();

        $this->vehicleTypeService->store($validated);

        AppHelper::success('Vehicle Type created successfully.');

        return redirect()->route('admin.settings.vehicle.type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleType $vehicleType)
    {
        $vehicleType = VehicleType::findOrFail($vehicleType->id);

        return view('vehicle.type.index', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleType $vehicleType)
    {
        $vehicleType = $this->vehicleTypeService->getById($vehicleType->id);

        return view('vehicle.type.edit', compact('vehicleType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        $vehicle_type = $this->vehicleTypeService->getById($vehicleType->id);

        $validated = VehicleType::validateData($request->all(), $vehicleType);

        // If validation fails, redirect back with errors
        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Get the validated data
        $validatedData = $validated->validated();

        // Update the vehicle type using the service
        $this->vehicleTypeService->update($vehicle_type, $validatedData);

        // Show a success message
        AppHelper::success('Vehicle Type updated successfully.');

        // Redirect back to the vehicle type index page
        return redirect()->route('admin.settings.vehicle.type.index');
    }

    public function update1(Request $request, VehicleType $vehicleType)
    {
        $vehicle_type = $this->vehicleTypeService->getById($vehicleType->id);

        $vaildated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->vehicleTypeService->update($vehicle_type, $vaildated);

        return redirect()->route('admin.settings.vehicle.type.index')->with('success', 'Vehicle type updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function status(VehicleType $vehicleType, $status)
    {
        $vehicleType->is_active = in_array($status, [0, 1]) ? $status : 0;

        $vehicleType->save();

        AppHelper::success('Vehicle Type status updated successfully.');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        $this->vehicleTypeService->delete($vehicleType->id);

        AppHelper::success('Vehicle Type deleted successfully.');

        // return redirect()->route('admin.settings.vehicle.type.index')->with('success', 'Vehicle type deleted successfully.');
        return redirect()->route('admin.settings.vehicle.type.index');
    }
}
