<?php

namespace App\Http\Controllers;

use App\Models\Renewal;
use App\Http\Services\RenewalService;
use App\Http\Requests\RenewalRequest;
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
    public function index()
    {
        //
        return view('renewal.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('renewal.add');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Renewal $renewal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Renewal $renewal)
    {
        //
    }
}
