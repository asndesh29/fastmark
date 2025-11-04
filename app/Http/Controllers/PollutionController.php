<?php

namespace App\Http\Controllers;

use App\Http\Services\PollutionService;
use App\Models\PollutionCheck;
use Illuminate\Http\Request;

class PollutionController extends Controller
{
    protected $pollutionService;

    public function __construct(PollutionService $pollutionService)
    {
        $this->pollutionService = $pollutionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->pollutionService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.pollution.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.pollution.index', compact('renewal_lists'));
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
            $this->pollutionService->store($data);
            return redirect()->back()->with('success', 'Bluebook Renewal record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PollutionCheck $pollution)
    {
        // Eager load renewals and their types
        $pollution->load('renewals.renewalType', 'vehicle');

        return view('renewal.pollution.show', compact('pollution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PollutionCheck $pollution)
    {
        // dd($pollutionCheck);
        $renewal = $this->pollutionService->getById($pollution->id);

        return view('renewal.pollution.edit', compact('renewal'));
    }

    public function update(Request $request, PollutionCheck $pollution)
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

        $this->pollutionService->update($pollution, $data);

        return redirect()->route('admin.renewal.pollution.index')->with('success', 'Check Pollution updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollutionCheck $pollution)
    {

    }
}
