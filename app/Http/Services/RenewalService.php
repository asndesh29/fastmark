<?php

namespace App\Http\Services;

use App\Models\Customer;
use App\Models\Renewal;
use App\Models\Vehicle;

class RenewalService
{
    public function index() 
    {

    }

    public function list()
    {

    }

    public function store($data) 
    {
        // Create customer
        $customer = Customer::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'],
            'email'      => $data['email'],
        ]);

        // Store multiple vehicles & create renewal entry for each
        foreach ($data['vehicle_type'] as $index => $vehicleType) {
            $vehicle = Vehicle::create([
                'customer_id'       => $customer->id,
                'vehicle_type'      => $vehicleType,
                'registration_no'   => $data['registration_no'][$index],
                'chassis_no'        => $data['chassis_no'][$index],
                'engine_no'         => $data['engine_no'][$index],
                'engine_cc'         => $data['engine_cc'][$index],
                'last_renewed_at'   => $data['renewed_date'][$index],
            ]);

            // create a renewal record linked to this vehicle & customer
            Renewal::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
            ]);
        }

        return $customer;
    }

    public function getById()
    {}

    public function show()
    {

    }

    public function update() 
    {

    }

    public function destroy()
    {

    }
}