<?php

namespace App\Http\Controllers;

use App\Http\Services\RenewalTypeService;
use App\Models\Renewal;
use App\Models\Vehicle;
use App\Models\RenewalType;
use App\Models\VehicleType;
use App\Models\InsuranceProvider;

use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    protected $renewalService, $vehicleService, $renewalTypeService;

    public function __construct(RenewalService $renewalService, VehicleService $vehicleService, RenewalTypeService $renewalTypeService)
    {
        $this->renewalService = $renewalService;
        $this->vehicleService = $vehicleService;
        $this->renewalTypeService = $renewalTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->renewalService->list($request, $perPage);

        return view('renewal.index', compact('renewal_lists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicleTypes = VehicleType::where('is_active', true)->get();

        return view('renewal.add', compact('vehicleTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'renewal_type_id' => 'required|exists:renewal_types,id',
            'type' => 'required|string',
            'book_number' => 'required_if:type,bluebook',
            'permit_number' => 'required_if:type,road_permit',

            'provider_id' => 'required_if:type,insurance',
            'policy_number' => 'required_if:type,insurance',
            'amount' => 'required_if:type,insurance',

            'check_date' => 'required_if:type,pollution',
            'certificate_number' => 'required_if:type,pollution',

            'inspection_result' => 'required_if:type,check_pass | in:pass,fail',

            'issue_date' => 'required|date',
            // 'expiry_date' => 'required|date',
            'last_renewed_at' => 'nullable|date',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);
        dd($data);

        try {
            $this->renewalService->store($data);
            return redirect()->back()->with('success', 'Renewal record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['renewals.renewable', 'renewals.renewalType']);

        // Get all renewal types (e.g., road permit, insurance, etc.)
        $renewal_types = RenewalType::all();

        // Group renewals by renewal_type_id for easy lookup
        $renewalsByType = $vehicle->renewals->keyBy('renewal_type_id');
        // dd($renewalsByType);

        $providers = InsuranceProvider::where('is_active', true)->get();
        // dd($providers);

        return view('renewal.show', compact('vehicle', 'renewal_types', 'renewalsByType', 'providers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Renewal $renewal)
    {
        $renewal = $this->renewalService->getById($renewal->id);

        return view('renewal.edit', compact('renewal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Renewal $renewal)
    {
        $renewal = $this->renewalService->getById($renewal->id);

        $this->renewalService->update($renewal, $request->all());

        return redirect()->route('admin.renewal.index')->with('success', 'Renewal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Renewal $renewal)
    {
        $this->renewalService->delete($renewal->id);

        return redirect()->route('admin.renewal.index')->with('success', 'Renewal deleted successfully.');
    }


    public function create_renewal_type()
    {
        $renewal_types = RenewalType::all();
        return view('renewal.type.index', compact('renewal_types'));
    }

    public function store_renewal_type(Request $request)
    {
        $input = $request->all();

        $this->renewalTypeService->store($input);

        return back();
    }

    public function edit_renewal_type(RenewalType $renewalType)
    {
        $renewal_type = RenewalType::where('id', $renewalType->id)->first();
        return view('renewal.type.edit', compact('renewal_type'));
    }

    public function update_renewal_type(Request $request, RenewalType $renewalType)
    {
        $renewal_type = RenewalType::findOrFail($renewalType->id);

        if (!$renewal_type) {
            return false;
        }

        $renewal_type->name = $request->name;
        $renewal_type->charge = $request->charge;
        $renewal_type->save();

        return redirect()->route('admin.renewal.type.index');
    }

    public function delete_renewal_type(RenewalType $renewalType)
    {
        $renewal_type = RenewalType::findOrFail($renewalType->id);

        if (!$renewal_type) {
            return false;
        }

        $renewal_type->delete();

        return back();
    }

    public function update_renewal_type_status(RenewalType $renewalType, $status)
    {
        $renewalType->is_active = in_array($status, [0, 1]) ? $status : 0;

        $renewalType->save();

        return back()->with('success', 'Renewal Type status updated successfully.');
    }

    public function getRenewDetails($vehicleId)
    {
        $today = now(); // today's date

        $data = [
            'road_permit' => $this->getRenewInfo(RoadPermit::where('vehicle_id', $vehicleId)->latest()->first(), $today),
            'pollution' => $this->getRenewInfo(PollutionCertificate::where('vehicle_id', $vehicleId)->latest()->first(), $today),
            'check_pass' => $this->getRenewInfo(CheckPass::where('vehicle_id', $vehicleId)->latest()->first(), $today),
            'bluebook' => $this->getRenewInfo(BlueBook::where('vehicle_id', $vehicleId)->latest()->first(), $today),
            'insurance' => $this->getRenewInfo(Insurance::where('vehicle_id', $vehicleId)->latest()->first(), $today),
            'vehicle_tax' => $this->getRenewInfo(VehicleTax::where('vehicle_id', $vehicleId)->latest()->first(), $today),
        ];

        return response()->json($data);
    }

    private function getRenewInfo($record, $today)
    {
        if (!$record) {
            return [
                'last_renewed_at' => null,
                'expiry_date' => null,
                'is_expired' => null
            ];
        }

        return [
            'last_renewed_at' => $record->last_renewed_at,
            'expiry_date' => $record->expiry_date,
            'is_expired' => Carbon::parse($record->expiry_date)->lt($today)
        ];
    }

}
