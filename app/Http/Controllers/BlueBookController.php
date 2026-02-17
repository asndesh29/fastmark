<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Services\BluebookService;
use App\Http\Services\RenewalService;
use App\Http\Services\VehicleService;
use App\Models\Bluebook;
use App\Services\BaseRenewalService;
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
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->bluebookService->list($request, $perPage);

        // Handle AJAX filter requests
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
        $validator = Bluebook::validateData($request->all());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $bluebook = $this->bluebookService->store(
                $validator->validated()
            );

            AppHelper::success('Bluebook renewal record created successfully.');

            return redirect()->back();

        } catch (\Throwable $e) {

            report($e); // log error

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
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
        // dd($request->all());
        $validator = Bluebook::validateData($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $this->bluebookService->update($bluebook, $validated);

        AppHelper::success('Bluebook record updated successfully.');

        return redirect()->route('admin.renewal.bluebook.index');
    }

    public function update1(Request $request, Bluebook $bluebook)
    {
        $validated = $request->validate([
            'expiry_date_bs' => 'required',
            'payment_status' => 'required',
            'type' => 'required'
        ]);

        $service = app(BaseRenewalService::class);

        $service->renew($bluebook, $validated);

        return back()->with('success', 'Bluebook renewed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bluebook $bluebook)
    {

    }
}
