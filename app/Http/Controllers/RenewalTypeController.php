<?php

namespace App\Http\Controllers;

use App\Http\Services\RenewalTypeService;
use App\Models\RenewalType;

use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use Illuminate\Http\Request;

class RenewalTypeController extends Controller
{
    protected $renewalService, $vehicleService, $renewalTypeService;

    public function __construct(RenewalService $renewalService, VehicleService $vehicleService, RenewalTypeService $renewalTypeService)
    {
        $this->renewalService = $renewalService;
        $this->vehicleService = $vehicleService;
        $this->renewalTypeService = $renewalTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_types = $this->renewalTypeService->list($request, $perPage);

        return view('renewal.type.index', compact('renewal_types'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $this->renewalTypeService->store($data);

        return back();
    }

    public function edit(RenewalType $renewalType)
    {
        $renewal_type = RenewalType::where('id', $renewalType->id)->first();
        return view('renewal.type.edit', compact('renewal_type'));
    }

    public function update(Request $request, RenewalType $renewalType)
    {
        $renewalType = $this->renewalTypeService->getById($renewalType->id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->renewalTypeService->update($renewalType, $data);

        return redirect()->route('admin.settings.renewal-type.index')->with('success', 'Renewal Type record updated successfully.');
    }

    public function destroy(RenewalType $renewalType)
    {
        $renewal_type = RenewalType::findOrFail($renewalType->id);

        if (!$renewal_type) {
            return false;
        }

        $renewal_type->delete();

        return back();
    }

    public function status(RenewalType $renewalType, $status)
    {
        $renewalType->is_active = in_array($status, [0, 1]) ? $status : 0;

        $renewalType->save();

        return back()->with('success', 'Renewal Type status updated successfully.');
    }

}
