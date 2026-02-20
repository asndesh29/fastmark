<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
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
        $validator = VehiclePass::validateData($request->all());

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->checkpassService->store($validator->validated());

            AppHelper::success('Jach Pass record created successfully.');

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
        $validator = VehiclePass::validateData($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $this->checkpassService->update($checkpass, $validated);

        AppHelper::success('Jach Pass record updated successfully.');

        return redirect()->route('admin.renewal.checkpass.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehiclePass $checkpass)
    {

    }
}
