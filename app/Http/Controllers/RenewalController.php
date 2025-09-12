<?php

namespace App\Http\Controllers;

use App\Models\Renewal;
use App\Http\Services\RenewalService;
use App\Http\Requests\RenewalRequest;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    protected $renewalService;

    public function __construct(RenewalService $renewalService)
    {
        $this->renewalService = $renewalService;
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
        $this->renewalService->store($request->all());

        return redirect()->route('admin.renewal.index')->with('success', 'Renewal record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Renewal $renewal)
    {
        //
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
}
