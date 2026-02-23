<?php

namespace App\Http\Controllers;

use App\Exports\VehiclesExport;
use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Helpers\AppHelper;
use App\Http\Requests\UpdateVehicleRenewalRequest;
use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use App\Models\Bluebook;
use App\Models\InsuranceProvider;
use App\Models\PollutionCheck;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleTax;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

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
        $vehicle = Vehicle::with('renewals.renewalType', 'renewals.renewable')->findOrFail($vehicle->id);

        $renewals = $vehicle->renewals()
            ->with(['renewalType', 'renewable'])
            ->latest()
            ->paginate(10);

        // dd($vehicle);
        return view('vehicle.show', compact('renewals', 'vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Vehicle $vehicle)
    {
        $vehicle = $this->vehicleService->getById($vehicle->id);

        $vehicle_types = VehicleType::where('is_active', true)->get();
        $vehicle_categories = VehicleCategory::where('is_active', true)->get();

        return view('vehicle.edit', compact('vehicle', 'vehicle_types', 'vehicle_categories'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicle = $this->vehicleService->getById($vehicle->id);

        // Validate request
        $validator = Vehicle::validateData($request->all(), $vehicle->id);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Update vehicle
        $this->vehicleService->update($vehicle, $validated);

        AppHelper::success('Vehicle updated successfully.');

        return redirect()->route('admin.vehicle.index');
    }

    public function update1(Request $request, RenewalType $renewalType)
    {
        $renewalType = $this->renewalTypeService->getById($renewalType->id);

        try {
            $validator = RenewalType::validateData($request->all(), $renewalType->id);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            $this->renewalTypeService->update($renewalType, $validated);

            AppHelper::success('Renewal type record updated successfully.');

            return redirect()->route('admin.settings.renewal-type.index');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        // $this->renewalTypeService->update($renewalType, $data);

        // return redirect()->route('admin.settings.renewal-type.index')->with('success', 'Renewal Type record updated successfully.');
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
    public function renewal1(Vehicle $vehicle)
    {
        // dd($vehicle);
        $vehicle = Vehicle::findOrFail($vehicle->id);

        $renewalTypes = RenewalType::where('is_active', true)->pluck('name', 'slug');

        $vehicle_types = VehicleType::where('is_active', true)->get();
        // dd($renewalTypes);

        $vehicle_categories = VehicleCategory::where('is_active', true)->get();

        $insuranceProviders = InsuranceProvider::where('is_active', true)->get();

        $customer = $vehicle?->owner;

        return view('vehicle.renewal', compact('vehicle', 'vehicle_types', 'vehicle_categories', 'insuranceProviders', 'renewalTypes', 'customer'));
    }

    // public function updateRenewal(Request $request, Vehicle $vehicle)
    // {
    //     dd($request->all());
    //     $request->validate([
    //         'vehicle_id' => 'required|exists:vehicles,id',
    //         'renewals' => 'required|array',
    //     ]);

    //     foreach ($request->renewals as $type) {
    //         $extraData = [];

    //         if ($type == 'insurance') {
    //             $extraData['provider_id'] = $request->insurance['provider_id'] ?? null;
    //         }

    //         $this->vehicleService->updateRenewal([
    //             'vehicle_id' => $vehicle->id,
    //             // 'type' => $type,
    //             'expiry_date_bs' => $request->$type['expiry_date_bs'] ?? null,
    //             // 'last_expiry_date' => $request->$type['last_expiry_date'] ?? null,
    //             'payment_status' => $request->$type['payment_status'] ?? 'active',
    //             'remarks' => $request->$type['remarks'] ?? null,
    //             ...$extraData
    //         ]);
    //     }

    //     AppHelper::success('Selected renewal record updated successfully.');

    //     return redirect()->route('admin.vehicle.index')->with('success', 'Vehicle renewal updated successfully.');
    // }

    public function updateRenewal2(UpdateVehicleRenewalRequest $request, Vehicle $vehicle)
    {
        foreach ($request->renewals as $type) {

            $data = $request->input($type, []);

            $this->vehicleService->updateRenewal([
                'vehicle' => $vehicle,
                'type' => $type,
                'issue_date_bs' => $data['issue_date_bs'] ?? null,
                'expiry_date_bs' => $data['expiry_date_bs'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'remarks' => $data['remarks'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
                'policy_number' => $data['policy_number'] ?? null,
                'insurance_type' => $data['insurance_type'] ?? null,
            ]);
        }

        AppHelper::success('Selected renewal record updated successfully.');

        return redirect()->route('admin.vehicle.index');
    }

    public function updateRenewal1(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'renewals' => 'required|array',
        ]);

        foreach ($request->renewals as $type) {

            $data = $request->input($type, []);

            $this->vehicleService->updateRenewal([
                'vehicle' => $vehicle,
                'type' => $type,
                'issue_date_bs' => $data['issue_date_bs'] ?? null,
                'expiry_date_bs' => $data['expiry_date_bs'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'remarks' => $data['remarks'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
            ]);
        }

        AppHelper::success('Selected renewal record updated successfully.');

        return redirect()->route('admin.vehicle.index');
    }


    public function renewal(Vehicle $vehicle)
    {
        // Fetch dynamic data from DB
        $renewalTypes = RenewalType::all()->pluck('name', 'slug')->toArray();

        $insuranceProviders = InsuranceProvider::all();

        // Generate dynamic $renewalFields
        $renewalFields = $this->vehicleService->generateRenewalFields($insuranceProviders);

        $customer = $vehicle?->owner;

        return view('vehicle.renewal', compact(
            'vehicle',
            'customer',
            'renewalTypes',
            'insuranceProviders',
            'renewalFields'
        ));
    }

    public function updateRenewal(UpdateVehicleRenewalRequest $request, Vehicle $vehicle)
    {
        foreach ($request->renewals as $type) {

            $data = $request->input($type, []);

            $this->vehicleService->updateRenewal([
                'vehicle' => $vehicle,
                'type' => $type,
                'issue_date_bs' => $data['issue_date_bs'] ?? null,
                'expiry_date_bs' => $data['expiry_date_bs'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'unpaid',
                'remarks' => $data['remarks'] ?? null,
                'provider_id' => $data['provider_id'] ?? null,
                'insurance_type' => $data['insurance_type'] ?? null,
                'policy_number' => $data['policy_number'] ?? null,
            ]);
        }

        AppHelper::success('Selected renewal records updated successfully.');
        return redirect()->route('admin.vehicle.index');
    }

}
