@if (count($customers) > 0)
    @foreach ($customers as $key => $customer)
        <tr>
            <td class="fw-medium">{{ $key + $customers->firstItem() }}</td>
            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>
                <div class="status">
                    <div class="form-check form-switch form-switch-mdform-switch form-switch-md">
                        <input type="checkbox" class="form-check-input code-switcher toggle-switch-input status_change_alert"
                            data-url="{{ route('admin.customer.status', [$customer->id, $customer->is_active ? 0 : 1]) }}"
                            data-message="{{ $customer->is_active ? 'you want to deactivate this customer' : 'you want to activate this customer' }}"
                            id="status_{{ $customer->id }}" {{ $customer->is_active ? 'checked' : '' }}>
                    </div>
                </div>
            </td>
            <td>
                <ul class="list-inline hstack gap-2 mb-0">
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                        title="Edit">
                        <a href="{{ route('admin.customer.edit', $customer->id) }}">
                            <button type="button" class="btn btn-outline-primary btn-sm btn-icon waves-effect waves-light">
                                <i class="ri-edit-fill"></i>
                            </button>
                        </a>
                    </li>

                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                        title="View">
                        <a href="{{ route('admin.customer.show', $customer->id) }}">
                            <button type="button" class="btn btn-outline-warning btn-sm btn-icon waves-effect waves-light">
                                <i class="ri-eye-fill"></i>
                            </button>
                        </a>
                    </li>

                    {{-- <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                        title="Delete">
                        <a href="">
                            <button type="button" class="btn btn-outline-danger btn-sm btn-icon waves-effect waves-light">
                                <i class="ri-delete-bin-5-line"></i>
                            </button>
                        </a>
                    </li> --}}

                    <!-- Delete button -->
                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                        title="Remove">

                        <button type="button" class="btn btn-outline-danger btn-sm btn-icon waves-effect waves-light delete-btn"
                            data-id="{{ $customer->id }}" data-name="{{ $customer->name ?? '' }}">
                            <!-- Optional: display name in alert -->
                            <i class="ri-delete-bin-5-fill fs-16"></i>
                        </button>

                        <!-- Delete form -->
                        <form action="{{ route('admin.customer.destroy', [$customer->id]) }}" method="post"
                            id="renew-{{ $customer->id }}" style="display: none;">
                            @csrf @method('delete')
                        </form>
                    </li>
                </ul>
            </td>
        </tr>
    @endforeach
@else
    <!-- No result found message -->
    <tr>
        <td colspan="6" class="text-center">
            <div class="noresult text-center">
                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                    colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                <h5 class="mt-2">Sorry! No Result Found</h5>
                <p class="text-muted mb-0">No matching records found.</p>
            </div>
        </td>
    </tr>
@endif

@if ($customers->hasPages())
    <tr>
        <td colspan="9">
            <div class="d-flex justify-content-end">
                {!! $customers->links('pagination::bootstrap-5') !!}
            </div>
        </td>
    </tr>
@endif