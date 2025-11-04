<?php

namespace App\Http\Controllers;

use App\Http\Services\PollutionService;
use App\Http\Services\RoadpermitService;
use App\Models\PollutionCheck;
use App\Models\RoadPermit;
use Illuminate\Http\Request;

class RoadPermitController extends Controller
{
    protected $roadpermitService;

    public function __construct(RoadpermitService $roadpermitService)
    {
        $this->roadpermitService = $roadpermitService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->roadpermitService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.road-permit.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.road-permit.index', compact('renewal_lists'));
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
            'invoice_number' => 'required|string|max:255',
            'issue_date' => 'required|string',
            'last_expiry_date' => 'nullable|string',
            'tax_amount' => 'nullable|numeric',
            'renewal_charge' => 'nullable|numeric',
            'income_tax' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'remarks' => 'nullable|string',
        ]);

        try {
            $this->roadpermitService->store($data);
            return redirect()->back()->with('success', 'Road Permit Renewal record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoadPermit $roadpermit)
    {
        // Eager load renewals and their types
        $roadpermit->load('renewals.renewalType', 'vehicle');

        return view('renewal.road-permit.show', compact('roadpermit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoadPermit $roadpermit)
    {
        // dd($pollutionCheck);
        $renewal = $this->roadpermitService->getById($roadpermit->id);

        return view('renewal.road-permit.edit', compact('renewal'));
    }

    public function update(Request $request, RoadPermit $roadpermit)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'invoice_number' => 'required|string|max:255',
            'issue_date' => 'required|string',
            'last_expiry_date' => 'nullable|string',
            'tax_amount' => 'nullable|numeric',
            'renewal_charge' => 'nullable|numeric',
            'income_tax' => 'nullable|numeric',
            'status' => 'required|in:paid,unpaid',
            'remarks' => 'nullable|string',
        ]);

        $this->roadpermitService->update($roadpermit, $data);

        return redirect()->route('admin.renewal.road-permit.index')->with('success', 'Check Pollution updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollutionCheck $roadpermit)
    {

    }
}
