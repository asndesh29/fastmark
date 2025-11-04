@if ($renewal_lists->count() > 0)
    @foreach ($renewal_lists as $key => $vehicle)
        @php
            $vehicle_tax = $vehicle->vehicleTax;
            $renewal = $vehicle_tax?->renewal;
        @endphp
        <tr>
            <td>{{ $key + $renewal_lists->firstItem() }}</td>
            <td>{{ $vehicle_tax->invoice_no ?? '-' }}</td>
            <td>{{ $vehicle->owner->first_name }} {{ $vehicle->owner->last_name }}</td>
            <td>{{ $vehicle->vehicleType->name }}</td>
            <td>{{ $vehicle->registration_no }}</td>
            <td>{{ $vehicle_tax->last_expiry_date ?? '-' }}</td>
            <td>{{ $vehicle_tax->expiry_date ?? '-' }}</td>
            <td>{{ $renewal ? 'Renewed' : 'No Renewal' }}</td>
            <td>
                @if($renewal)
                    <span class="badge bg-{{ $renewal->status == 'paid' ? 'success' : 'danger' }}">
                        {{ ucfirst($renewal->status) }}
                    </span>
                @endif
            </td>
            <td>
                <ul class="list-inline hstack gap-2 mb-0">
                    <li class="list-inline-item" title="Add Renewal">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-icon addBtn"
                                data-vehicle-id="{{ $vehicle->id }}"
                                data-bs-toggle="modal"
                                data-bs-target="#vehicletaxModal">
                            <i class="ri-add-fill"></i>
                        </button>
                    </li>

                    @if($vehicle_tax)
                        <li class="list-inline-item" title="Edit">
                            <a href="{{ route('admin.renewal.vehicle-tax.edit', $vehicle_tax->id) }}">
                                <button type="button" class="btn btn-outline-primary btn-sm btn-icon">
                                    <i class="ri-edit-fill"></i>
                                </button>
                            </a>
                        </li>
                        {{-- <li class="list-inline-item" title="View">
                            <a href="{{ route('admin.renewal.vehicle-tax.show', $vehicle_tax->id) }}">
                                <button type="button" class="btn btn-outline-warning btn-sm btn-icon">
                                    <i class="ri-eye-fill"></i>
                                </button>
                            </a>
                        </li> --}}
                    @endif
                </ul>
            </td>
        </tr>
    @endforeach
@else
    <!-- No result found message -->
    <tr>
        <td colspan="10" class="text-center">
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

@if ($renewal_lists->hasPages())
    <tr>
        <td colspan="10">
            <div class="d-flex justify-content-end">
                {!! $renewal_lists->links('pagination::bootstrap-5') !!}
            </div>
        </td>
    </tr>
@endif
