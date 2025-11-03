<?php

namespace App\Http\Controllers;

use App\Http\Services\InsuranceService;
use App\Http\Services\VehicleTaxService;

use App\Models\Insurance;
use App\Models\InsuranceProvider;
use App\Models\VehicleTax;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    protected $insuranceService;

    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->insuranceService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.insurance.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        $providers = InsuranceProvider::where('is_active', true)->get();

        return view('renewal.insurance.index', compact('renewal_lists', 'providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'provider_id' => 'required|exists:insurance_providers,id',
            'policy_number' => 'nullable|string',
            'issue_date' => 'required|string',
            'amount' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'remarks' => 'nullable|string',
        ]);

        try {
            $this->insuranceService->store($data);
            return redirect()->back()->with('success', 'Insurance Renewal record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        // Eager load renewals and their types
        $insurance->load('renewals.renewalType', 'vehicle');

        return view('renewal.insurance.show', compact('insurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        // dd($insurance);
        $renewal = $this->insuranceService->getById($insurance->id);
        // dd($renewal);

        $providers = InsuranceProvider::where('is_active', true)->get();

        // Assuming insurance has a field 'provider_id'
        $selectedProviderId = $insurance->provider_id ?? null;
        // dd($selectedProviderId);

        return view('renewal.insurance.edit', compact('renewal', 'providers', 'selectedProviderId'));
    }

    public function update(Request $request, Insurance $insurance)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'provider_id' => 'required|exists:insurance_providers,id',
            'policy_number' => 'nullable|string',
            'issue_date' => 'required|string',
            'amount' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'remarks' => 'nullable|string',
        ]);

        $this->insuranceService->update($insurance, $data);

        return redirect()->route('admin.insurance.index')->with('success', 'Insurance updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleTax $vehicletax)
    {

    }
}
