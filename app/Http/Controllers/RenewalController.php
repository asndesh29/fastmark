<?php

namespace App\Http\Controllers;

use App\Models\Renewal;
use App\Http\Services\RenewalService;
use App\Models\RenewalType;
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


    /* Start Vehicle Tax */
    /**
     * Show the form for creating a new resource.
     */
    public function create_tax()
    {
        return view('renewal.vehicle-tax.index');
    }
    /* End Vehicle Tax */


    /* Start Bluebook */
    /**
     * Display a listing of the resource.
     */
    public function get_bluebooks(Request $request)
    {
        return view('renewal.bluebook.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_bluebook(Request $request)
    {
        return view('renewal.bluebook.index');
    }
    /* End Bluebook */


    /* Start Insurance */
    /**
     * Display a listing of the resource.
     */
    public function get_insurances(Request $request)
    {
        return view('renewal.insurance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_insurance(Request $request)
    {
        return view('renewal.insurance.index');
    }
    /* End Insurance */


    /* Start Pollution Check */
    /**
     * Display a listing of the resource.
     */
    public function get_pollution_checks(Request $request)
    {
        return view('renewal.pollution-check');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_pollution_check(Request $request)
    {
        return view('renewal.pollution-check.index');
    }
    /* End Pollution Check */


    /* Start Road Permit */
    /**
     * Display a listing of the resource.
     */
    public function get_road_permits(Request $request)
    {
        return view('renewal.road-permit');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_road_permit(Request $request)
    {
        return view('renewal.road-permit.index');
    }
    /* End Road Permits */


    /* Start Vehicle/Check Passes */
    /**
     * Display a listing of the resource.
     */
    public function get_check_passes(Request $request)
    {
        return view('renewal.vehicle-pass.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_check_pass(Request $request)
    {
        return view('renewal.vehicle-pass.index');
    }
    /* End Vehicle/Check Passes */


    public function create_renewal_type()
    {
        $renewal_types = RenewalType::all();
        return view('renewal.type.index', compact('renewal_types'));
    }

    public function store_renewal_type(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $renewalType = new RenewalType();
        $renewalType->name = $request->name;
        $renewalType->save();

        return back();
    }

    public function edit_renewal_type(RenewalType $renewalType)
    {
        $renewal_type = RenewalType::where('id', $renewalType->id)->first();
        return view('renewal.type.edit', compact('renewal_type'));
    }

    public function update_renewal_type(Request $request, RenewalType $renewalType)
    {
        $renewal_type = RenewalType::findOrFail($renewalType->id);

        if (!$renewal_type) {
            return false;
        }

        $renewal_type->name = $request->name;
        $renewal_type->save();

        return redirect()->route('admin.renewal.type.index');
    }

    public function delete_renewal_type(RenewalType $renewalType)
    {
        $renewal_type = RenewalType::findOrFail($renewalType->id);

        if (!$renewal_type) {
            return false;
        }

        $renewal_type->delete();

        return back();
    }

    public function update_renewal_type_status(RenewalType $renewalType, $status)
    {
        $renewalType->is_active = in_array($status, [0, 1]) ? $status : 0;

        $renewalType->save();

        return back()->with('success', 'Renewal Type status updated successfully.');
    }
}
