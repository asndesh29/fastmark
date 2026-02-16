@if ($vehicles->count() > 0)
    @foreach ($vehicles as $key => $vehicle)
        <tr>
            <td>{{ $key + $vehicles->firstItem() }}</td>
            <td>{{ $vehicle->registration_no }}</td>
            <td>{{ $vehicle->vehicleType?->name }}</td>
            <td>{{ $vehicle->vehicleCategory?->name }}</td>
            <td>{{ $vehicle->owner?->first_name }} {{ $vehicle->owner?->last_name }}</td>
            <td>{{ $vehicle->permit_no }}</td>
            <td>{{ $vehicle->engine_no }}</td>
            <td>{{ $vehicle->chassis_no }}</td>
            <td>
                @if($vehicle->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
        </tr>
    @endforeach
@else
    <!-- No result found message -->
    <tr>
        <td colspan="9" class="text-center">
            <div class="noresult text-center">
                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                            colors="primary:#121331,secondary:#08a88a"
                            style="width:75px;height:75px"></lord-icon>
                <h5 class="mt-2">Sorry! No Result Found</h5>
                <p class="text-muted mb-0">No matching records found.</p>
            </div>
        </td>
    </tr>
@endif

@if ($vehicles->hasPages())
    <tr>
        <td colspan="9">
            <div class="d-flex justify-content-end">
                {!! $vehicles->links('pagination::bootstrap-5') !!}
            </div>
        </td>
    </tr>
@endif
