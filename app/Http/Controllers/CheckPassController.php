<?php

namespace App\Http\Controllers;

use App\Http\Services\CheckpassService;
use App\Models\VehiclePass;
use Illuminate\Http\Request;

class CheckPassController extends Controller
{
    protected $checkpassService;

    public function __construct(CheckpassService $checkpassService)
    {
        $this->checkpassService = $checkpassService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->checkpassService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.check-pass.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.check-pass.index', compact('renewal_lists'));
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
            $this->checkpassService->store($data);
            return redirect()->back()->with('success', 'Road Permit Renewal record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VehiclePass $checkpass)
    {
        // Eager load renewals and their types
        $checkpass->load('renewals.renewalType', 'vehicle');

        return view('renewal.check-pass.show', compact('checkpass'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehiclePass $checkpass)
    {
        // dd($pollutionCheck);
        $renewal = $this->checkpassService->getById($checkpass->id);

        return view('renewal.check-pass.edit', compact('renewal'));
    }

    public function update(Request $request, VehiclePass $checkpass)
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

        $this->checkpassService->update($checkpass, $data);

        return redirect()->route('admin.renewal.checkpass.index')->with('success', 'Check Pollution updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehiclePass $checkpass)
    {

    }
}
