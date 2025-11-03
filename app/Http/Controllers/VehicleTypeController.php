<?php

namespace App\Http\Controllers;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->vehicleTypeService->store($validated);

        return redirect()->route('admin.vehicle.type.index')->with('success', 'Vehicle Type created successfully.');
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

        $vaildated = $request->validate([
            'name' => 'required|string|max:255',
            'service_charge' => 'required|numeric|min:0, max:99999.99'
        ]);

        $this->vehicleTypeService->update($vehicle_type, $vaildated);

        return redirect()->route('admin.vehicle.type.index')->with('success', 'Vehicle type updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function status(VehicleType $vehicleType, $status)
    {
        $vehicleType->is_active = in_array($status, [0, 1]) ? $status : 0;

        $vehicleType->save();

        return back()->with('success', 'Vehicle type status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        $this->vehicleTypeService->delete($vehicleType->id);

        return redirect()->route('admin.vehicle.type.index')->with('success', 'Vehicle type deleted successfully.');
    }
}
