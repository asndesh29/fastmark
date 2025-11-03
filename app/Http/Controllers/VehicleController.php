<?php

namespace App\Http\Controllers;

use App\Http\Services\VehicleService;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $vehicles = $this->vehicleService->list($request, $perPage);

        return view('vehicle.index', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->vehicleService->store($data);

        return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle = Vehicle::findOrFail($vehicle->id);

        return view('vehicle.index', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        // dd($category);
        $vehicle = $this->vehicleService->getById($vehicle->id);

        return view('vehicle.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicle = $this->vehicleService->getById($vehicle->id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->vehicleService->update($vehicle, $data);

        return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->vehicleService->delete($vehicle->id);

        return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function status(Vehicle $vehicle, $status)
    {
        $vehicle->is_active = in_array($status, [0, 1]) ? $status : 0;

        $vehicle->save();

        return back()->with('success', 'Vehicle status updated successfully.');
    }
}
