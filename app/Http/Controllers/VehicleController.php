<?php

namespace App\Http\Controllers;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Helpers\AppHelper;
use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use App\Models\Bluebook;
use App\Models\PollutionCheck;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use App\Models\VehicleTax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    protected $vehicleService, $renewalService;

    public function __construct(VehicleService $vehicleService, RenewalService $renewalService)
    {
        $this->vehicleService = $vehicleService;
        $this->renewalService = $renewalService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $vehicles = $this->vehicleService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('vehicle.partials.table', compact('vehicles'))->render();
            return response()->json(['html' => $html]);
        }

        return view('vehicle.index', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate using the model's validateData method
        $validator = Vehicle::validateData($request->all());

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, store the data
        $validated = $validator->validated();

        $this->vehicleService->store($validated);

        AppHelper::success('Vehicle Created Successfully.');

        return redirect()->route('admin.vehicle.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        // dd($vehicle);
        $vehicle = Vehicle::findOrFail($vehicle->id);

        return view('vehicle.show', compact('vehicle'));
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

    /**
     * Display the specified resource.
     */
    public function renewal(Vehicle $vehicle)
    {
        // dd($vehicle);
        $vehicle = Vehicle::findOrFail($vehicle->id);

        $customer = $vehicle?->owner;

        return view('vehicle.renewal', compact('vehicle', 'customer'));
    }

    // public function updateRenewal(Request $request, Vehicle $vehicle)
    // {
    //     dd($request->all());
    //     $vehicle = Vehicle::findOrFail($vehicle->id);

    //     $validated = $request->validate([
    //         'bluebook_expiry' => 'nullable|date',
    //         'jach_pass_expiry' => 'nullable|date',
    //         'insurance_expiry' => 'nullable|date',
    //         'pollution_expiry' => 'nullable|date',
    //         'road_permit_expiry' => 'nullable|date',
    //         'vehicle_tax_expiry' => 'nullable|date',
    //     ]);

    //     $vehicle->update($validated);

    //     return redirect()->back()->with('success', 'Renewals updated successfully.');
    // }

    public function updateRenewal(Request $request, Vehicle $vehicle)
    {
        // dd($request->all());
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'renewals' => 'required|array',
        ]);

        foreach ($request->renewals as $type) {

            $this->vehicleService->updateRenewal([
                'vehicle_id' => $vehicle->id,
                'type' => $type,
                'issue_date' => $request->$type['issue_date'] ?? null,
                'last_expiry_date' => $request->$type['last_expiry_date'] ?? null,
                'status' => $request->$type['status'] ?? 'active',
                'remarks' => null,
            ]);
        }

        AppHelper::success('Selected renewal record updated successfully.');

        return redirect()->back()->with('success', 'Selected renewals updated successfully.');
    }
}
