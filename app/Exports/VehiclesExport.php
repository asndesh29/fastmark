<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehiclesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Vehicle::with(['vehicleType', 'vehicleCategory', 'owner'])
            ->whereNull('deleted_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Registration No',
            'Vehicle Type',
            'Vehicle Category',
            'Owner',
            'Permit No',
            'Engine No',
            'Chassis No',
            'Engine CC',
            'Capacity',
            'Status'
        ];
    }

    public function map($vehicle): array
    {
        return [
            $vehicle->registration_no,
            $vehicle->vehicleType?->name,
            $vehicle->vehicleCategory?->name,
            $vehicle->owner?->first_name . '' . $vehicle->owner?->last_name,
            $vehicle->permit_no,
            $vehicle->engine_no,
            $vehicle->chassis_no,
            $vehicle->engine_cc,
            $vehicle->capacity,
            $vehicle->is_active ? 'Active' : 'Inactive',
        ];
    }
}

