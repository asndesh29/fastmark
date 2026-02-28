@if ($renewal_lists->count() > 0)
    @foreach ($renewal_lists as $key => $vehicle)
        @php
            $vehiclepass = $vehicle->vehiclePass;
            $renewal = $vehiclepass?->renewals?->sortByDesc('id')->first();

            $rowClass = '';

            if ($renewal) {
                if ($renewal->is_expired) {
                    $rowClass = 'table-danger'; // 🔴 Expired
                } elseif ($renewal->days_remaining !== null && $renewal->days_remaining <= 7) {
                    $rowClass = 'table-warning'; // 🟡 Expiring soon
                }
            }
        @endphp

        <tr class="{{ $rowClass }}">
            <td>{{ $key + $renewal_lists->firstItem() }}</td>
            <td>{{ $vehiclepass->invoice_no ?? '-' }}</td>
            <td>{{ $vehicle->owner->first_name }} {{ $vehicle->owner->last_name }}</td>
            <td>{{ $vehicle->vehicleType->name }}</td>
            <td>{{ $vehicle->registration_no }}</td>
            <td>{{ $vehiclepass->expiry_date_bs ?? '-' }}</td>

            {{-- Renewal Status --}}
            <td>
                @if($renewal)
                    <span class="badge 
                        bg-{{ 
                            $renewal->display_status == 'expired' ? 'danger' :
                            ($renewal->display_status == 'renewed' ? 'success' : 'secondary')
                        }}">
                        {{ ucfirst($renewal->display_status) }}
                    </span>

                    {{-- Show expiry days only if expired OR <= 7 days --}}
                    @if($renewal->days_remaining !== null && $renewal->days_remaining <= 7)
                        <br>
                        <span class="badge bg-warning">
                            {{ $renewal->days_remaining }} days left
                        </span>
                    @endif
                @endif
            </td>

            {{-- Payment Status --}}
            <td>
                @if($renewal)
                    <span class="badge bg-{{ $renewal->is_paid == 1 ? 'success' : 'danger' }}">
                        {{ $renewal->is_paid == 1 ? 'Paid' : 'Unpaid' }}
                    </span>
                @endif
            </td>

            {{-- Actions --}}
            <td>
                <ul class="list-inline hstack gap-2 mb-0">
                    @if($vehiclepass)
                        <li class="list-inline-item" title="Edit">
                            <a href="{{ route('admin.renewal.checkpass.edit', $vehiclepass->id) }}">
                                <button type="button" class="btn btn-outline-primary btn-sm btn-icon">
                                    <i class="ri-edit-fill"></i>
                                </button>
                            </a>
                        </li>
                    @endif
                </ul>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="10" class="text-center">
            <div class="noresult text-center">
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