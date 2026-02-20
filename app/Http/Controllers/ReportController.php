<?php

namespace App\Http\Controllers;

use App\Exports\RenewalExpiryExport;
use App\Http\Controllers\Controller;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use App\Exports\VehiclesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $vehicle_types = VehicleType::where('is_active', true)->get();
        $vehicle_categories = VehicleCategory::where('is_active', true)->get();

        $query = Vehicle::with(['vehicleType', 'vehicleCategory', 'owner']);

        // Filters
        if ($request->filled('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        if ($request->filled('vehicle_category_id')) {
            $query->where('vehicle_category_id', $request->vehicle_category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $vehicles = $query->latest()->paginate(20);

        return view('report.index', compact(
            'vehicles',
            'vehicle_types',
            'vehicle_categories'
        ));
    }

    public function exportVehicles(Request $request)
    {
        return Excel::download(new VehiclesExport(), 'vehicle-report.xlsx');
    }

    public function renewalExpiry(Request $request)
    {
        $renewalTypes = RenewalType::where('is_active', true)->get();

        $query = Renewal::with(['vehicle', 'renewalType']);

        // Filter by renewal type
        if ($request->filled('renewal_type_id')) {
            $query->where('renewal_type_id', $request->renewal_type_id);
        }

        // Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('expiry_date_bs', [
                $request->from_date,
                $request->to_date
            ]);
        }

        $renewals = $query->orderBy('expiry_date_bs', 'asc')->paginate(20);

        return view('report.renewal-expiry', compact(
            'renewals',
            'renewalTypes'
        ));
    }

    public function exportRenewalExpiry(Request $request)
    {
        return Excel::download(
            new RenewalExpiryExport($request->all()),
            'renewal-expiry-report.xlsx'
        );
    }

}
