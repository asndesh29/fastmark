<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Services\PollutionService;
use App\Models\Pollution;
use Illuminate\Http\Request;

class PollutionController extends Controller
{
    protected $pollutionService;

    public function __construct(PollutionService $pollutionService)
    {
        $this->pollutionService = $pollutionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $renewal_lists = $this->pollutionService->list($request, $perPage);

        // Handle AJAX filter requests
        if ($request->ajax()) {
            $html = view('renewal.pollution.partials.table', compact('renewal_lists'))->render();
            return response()->json(['html' => $html]);
        }

        return view('renewal.pollution.index', compact('renewal_lists'));
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
            $validator = Pollution::validateData($request->all());

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            $this->pollutionService->store($validated);

            AppHelper::success('Pollution Check record updated successfully.');

            return redirect()->back();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pollution $pollution)
    {
        // Eager load renewals and their types
        $pollution->load('renewals.renewalType', 'vehicle');

        return view('renewal.pollution.show', compact('pollution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pollution $pollution)
    {
        // dd($pollutionCheck);
        $renewal = $this->pollutionService->getById($pollution->id);

        return view('renewal.pollution.edit', compact('renewal'));
    }

    public function update(Request $request, Pollution $pollution)
    {
        // dd($request->all());
        $validator = Pollution::validateData($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $this->pollutionService->update($pollution, $validated);

        AppHelper::success('Pollution Check record updated successfully.');

        return redirect()->route('admin.renewal.pollution.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pollution $pollution)
    {

    }
}
