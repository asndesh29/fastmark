<?php

namespace App\Http\Controllers;

use App\Http\Services\BluebookService;
use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use App\Models\Bluebook;
use Illuminate\Http\Request;

class BlueBookController extends Controller
{
    protected $renewalService, $vehicleService, $bluebookService;

    public function __construct(RenewalService $renewalService, VehicleService $vehicleService, BluebookService $bluebookService)
    {
        $this->renewalService = $renewalService;
        $this->vehicleService = $vehicleService;
        $this->bluebookService = $bluebookService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 1);

        $renewal_lists = $this->bluebookService->list($request, $perPage);

        // ✅ Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.bluebook.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.bluebook.index', compact('renewal_lists'));
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
            'book_number' => 'required|string|max:255',
            'issue_date' => 'required|string',
            'last_expiry_date' => 'nullable|string',
            // 'expiry_date' => 'nullable|string',
            'status' => 'required|in:paid,unpaid,pending',
            'remarks' => 'nullable|string',
            'type' => 'nullable|string'
        ]);

        try {
            $this->bluebookService->store($data);
            return redirect()->back()->with('success', 'Bluebook Renewal record created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bluebook $bluebook)
    {
        // Eager load renewals and their types
        $bluebook->load('renewals.renewalType', 'vehicle');

        return view('renewal.bluebook.show', compact('bluebook'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bluebook $bluebook)
    {
        // dd($bluebook);
        $renewal = $this->bluebookService->getById($bluebook->id);

        return view('renewal.bluebook.edit', compact('renewal'));
    }

    public function update(Request $request, Bluebook $bluebook)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'book_number' => 'required|string|max:255',
            'issue_date' => 'required|string',
            'last_expiry_date' => 'nullable|string',
            'expiry_date' => 'nullable|string',
            'status' => 'required|in:paid,unpaid,pending',
            'remarks' => 'nullable|string',
            'type' => 'nullable|string'
        ]);

        $this->bluebookService->update($bluebook, $data);

        return redirect()->route('admin.renewal.bluebook.index')->with('success', 'Bluebook updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bluebook $bluebook)
    {

    }
}
