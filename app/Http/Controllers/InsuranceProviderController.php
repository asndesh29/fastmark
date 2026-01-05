<?php

namespace App\Http\Controllers;

use App\Http\Services\InsuranceProviderService;
use App\Models\InsuranceProvider;
use Illuminate\Http\Request;

class InsuranceProviderController extends Controller
{
    protected $insuranceProviderService;

    public function __construct(InsuranceProviderService $insuranceProviderService)
    {
        $this->insuranceProviderService = $insuranceProviderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $providers = $this->insuranceProviderService->list($request, $perPage);

        return view('providers.index', compact('providers'));
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
        $data = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone_no' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $this->insuranceProviderService->store($data);

        return redirect()->route('admin.providers.index')->with('success', 'Insurance Provider record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InsuranceProvider $insuranceProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InsuranceProvider $insuranceProvider)
    {
        $provider = $this->insuranceProviderService->getById($insuranceProvider->id);

        return view('providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InsuranceProvider $insuranceProvider)
    {
        $provider = $this->insuranceProviderService->getById($insuranceProvider->id);

        $data = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'phone_no' => 'nullable|string',
            'email' => 'nullable|string',
        ]);

        $this->insuranceProviderService->update($provider, $data);

        return redirect()->route('admin.insurance-provider.index')->with('success', 'Insurance provider record updated successfully.');
    }

    public function status(InsuranceProvider $insuranceProvider, $status)
    {
        $insuranceProvider->is_active = in_array($status, [0, 1]) ? $status : 0;

        $insuranceProvider->save();

        return back()->with('success', 'Insurance Provider status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsuranceProvider $insuranceProvider)
    {
        $insurance_provider = InsuranceProvider::findOrFail($insuranceProvider->id);

        if (!$insurance_provider) {
            return false;
        }

        $insurance_provider->delete();

        return back();
    }

}
