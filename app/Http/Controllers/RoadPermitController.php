<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Services\PollutionService;
use App\Http\Services\RoadpermitService;
use App\Models\PollutionCheck;
use App\Models\RoadPermit;
use App\Models\VehicleType;
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

        $vehicle_types = VehicleType::where('is_active', true)->get();

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.road-permit.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.road-permit.index', compact('renewal_lists', 'vehicle_types'));
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
            $validator = RoadPermit::validateData($request->all());

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            $this->roadpermitService->store($validated);

            AppHelper::success('Road Permit record updated successfully.');

            return redirect()->back();
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
        $validator = RoadPermit::validateData($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $this->roadpermitService->update($roadpermit, $validated);

        AppHelper::success('Road Permit record updated successfully.');

        return redirect()->route('admin.renewal.road-permit.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollutionCheck $roadpermit)
    {

    }
}
