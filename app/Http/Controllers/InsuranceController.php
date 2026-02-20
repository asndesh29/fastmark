<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Services\InsuranceService;

use App\Models\Insurance;
use App\Models\InsuranceProvider;
use App\Models\VehicleTax;
use App\Models\VehicleType;
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

        $vehicle_types = VehicleType::where('is_active', true)->get();

        $providers = InsuranceProvider::where('is_active', true)->get();

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.insurance.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.insurance.index', compact('renewal_lists', 'providers', 'vehicle_types'));
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
        $validator = Insurance::validateData($request->all());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $this->insuranceService->store($validator->validated());

            AppHelper::success('Insurance record created successfully.');

            return redirect()->back();

        } catch (\Throwable $e) {
            report($e); // log error
            return back()->withInput()->with('error', 'Something went wrong. Please try again.');
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
        $validator = Insurance::validateData($request->all(), $insurance->id);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $this->insuranceService->update($insurance, $validated);

        AppHelper::success('Insurance record updated successfully.');

        return redirect()->route('admin.renewal.insurance.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleTax $vehicletax)
    {

    }
}
