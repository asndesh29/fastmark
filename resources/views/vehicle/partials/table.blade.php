@if ($vehicles->count() > 0)
    @foreach ($vehicles as $key => $vehicle)
        <tr>
            <td>{{ $key + $vehicles->firstItem() }}</td>
            <td>{{ $vehicle->vehicleType->name }}</td>
            <td>{{ $vehicle->vehicleCategory?->name }}</td>
            <td>{{ $vehicle->owner->first_name }} {{ $vehicle->owner->last_name }}</td>
            <td>{{ $vehicle->registration_no }}</td>
            <td>{{ $vehicle->chassis_no }}</td>
            <td>{{ $vehicle->engine_no }}</td>
            <td>
                <div class="form-check form-switch form-switch-right form-switch-md">
                    <input 
                        type="checkbox" class="form-check-input code-switcher toggle-switch-input status_change_alert" 
                        data-url="{{ route('admin.vehicle.status', [$vehicle->id, $vehicle->is_active ? 0 : 1]) }}"
                        data-message="{{$vehicle->is_active ? 'you want to deactivate this vehicle' : 'you want to activate this vehicle' }}"
                        id="status_change_alert_{{ $vehicle->id }}" 
                        {{ $vehicle->is_active ? 'checked' : '' }}>
                </div>
            </td>
            <td>
                <ul class="list-inline hstack gap-2 mb-0">
                     <li class="list-inline-item" title="View">
                        <a href="{{ route('admin.vehicle.edit', $vehicle->id) }}">
                            <button type="button" class="btn btn-outline-warning btn-sm btn-icon">
                                <i class="ri-edit-fill"></i>
                            </button>
                        </a>
                    </li>

                    <li class="list-inline-item" title="View">
                        <a href="{{ route('admin.vehicle.show', $vehicle->id) }}">
                            <button type="button" class="btn btn-outline-warning btn-sm btn-icon">
                                <i class="ri-eye-fill"></i>
                            </button>
                        </a>
                    </li>

                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                        <a href="{{ route('admin.vehicle.renewal', $vehicle->id) }}">
                            <button type="button" class="btn btn-outline-danger btn-sm btn-icon waves-effect waves-light">
                                <i class="ri-add-fill"></i>
                            </button>
                        </a>
                    </li>
                </ul>
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
