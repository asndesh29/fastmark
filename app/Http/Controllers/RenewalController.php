<?php

namespace App\Http\Controllers;

use App\Models\Renewal;
use App\Models\Vehicle;
use App\Models\RenewalType;
use App\Models\VehicleType;

use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    protected $renewalService, $vehicleService;

    public function __construct(RenewalService $renewalService, VehicleService $vehicleService)
    {
        $this->renewalService = $renewalService;
        $this->vehicleService = $vehicleService;
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
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date',
            'last_renewed_at' => 'nullable|date',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);

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

        return view('renewal.show', compact('vehicle', 'renewal_types', 'renewalsByType'));
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
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $renewalType = new RenewalType();
        $renewalType->name = $request->name;
        $renewalType->charge = $request->charge;
        $renewalType->save();

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
}
