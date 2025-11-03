<?php

namespace App\Http\Controllers;


use App\Http\Requests\FeeSlabRequest;
use App\Models\FeeSlab;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Services\FeeSlabService;

class FeeSlabController extends Controller
{
    protected $feeSlabService;

    public function __construct(FeeSlabService $feeSlabService)
    {
        $this->feeSlabService = $feeSlabService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $fee_slabs = $this->feeSlabService->list($request, $perPage);

        $vehicle_types = VehicleType::where('is_active', true)->get();

        return view('providers.index', compact('fee_slabs', 'vehicle_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicle_types = VehicleType::where('is_active', true)->get();
        return view('feeslab.create', compact('vehicle_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_type_id' => 'required|integer',
            'min_cc' => 'required|integer',
            'max_cc' => 'required|integer',
            'base_fee' => 'required|numeric',
        ]);

        $this->feeSlabService->store($data);

        return redirect()->route('admin.feeslab.index')->with('success', 'Fee Slab record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeSlab $feeSlab)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeSlab $fee)
    {
        $feeslab = $this->feeSlabService->getById($fee->id);

        return view('feeslab.edit', compact('feeslab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeSlab $fee)
    {
        $fee = $this->feeSlabService->getById($fee->id);

        $validated = $request->validate([
            'vehicle_type' => 'required|string',
            'min_cc' => 'required|integer',
            'max_cc' => 'required|string',
            'base_fee' => 'required|numeric',
        ]);

        $this->feeSlabService->update($fee, $validated);

        return redirect()->route('admin.feeslab.index')->with('success', 'Fee Slab record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeSlab $feeSlab)
    {
        //
    }
}
