<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Services\VehicleTaxService;

use App\Models\VehicleTax;
use Illuminate\Http\Request;

class VehicleTaxController extends Controller
{
    protected $vehicleTaxService;

    public function __construct(VehicleTaxService $vehicleTaxService)
    {
        $this->vehicleTaxService = $vehicleTaxService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->vehicleTaxService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.vehicle-tax.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.vehicle-tax.index', compact('renewal_lists'));
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
        try {
            $validator = VehicleTax::validateData($request->all());

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            $this->vehicleTaxService->store($validated);

            AppHelper::success('Vehicle Tax record updated successfully.');

            return redirect()->back();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleTax $vehicletax)
    {
        // Eager load renewals and their types
        $vehicletax->load('renewals.renewalType', 'vehicle');

        return view('renewal.vehicle-tax.show', compact('vehicletax'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleTax $vehicletax)
    {
        // dd($pollutionCheck);
        $renewal = $this->vehicleTaxService->getById($vehicletax->id);

        return view('renewal.vehicle-tax.edit', compact('renewal'));
    }

    public function update(Request $request, VehicleTax $vehicletax)
    {
        $validator = VehicleTax::validateData($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $this->vehicleTaxService->update($vehicletax, $validated);

        AppHelper::success('Vehicle Tax record updated successfully.');

        return redirect()->route('admin.renewal.vehicle-tax.index');

        // return redirect()->back()->with('success', 'Vehicle Tax updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleTax $vehicletax)
    {

    }
}
