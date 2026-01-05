<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Customer;
use App\Http\Services\CustomerService;
use App\Http\Requests\RenewalRequest;
use App\Models\VehicleCategory;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->show_limit ?? config('default_pagination', 10);

        $customers = $this->customerService->list($request, $perPage);

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicle_types = VehicleType::where('is_active', true)->get();

        $vehicle_categories = VehicleCategory::where('is_active', true)->get();

        return view('customer.create', compact('vehicle_types', 'vehicle_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Customer::validateData($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $this->customerService->store($validated);

        AppHelper::success('Customer created successfully;');

        return redirect()->route('admin.customer.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer = $this->customerService->getById($customer->id);

        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $customer = $this->customerService->getById($customer->id);

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $customer = $this->customerService->getById($customer->id);

        $this->customerService->update($customer, $request->all());

        return redirect()->route('admin.customer.index')->with('success', 'Customer record updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function status(Customer $customer, $status)
    {
        $customer->is_active = in_array($status, [0, 1]) ? $status : 0;

        $customer->save();

        return back()->with('success', 'Customer status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer->id);

        return redirect()->route('admin.customer.index')->with('success', 'Customer record deleted successfully.');
    }
}
