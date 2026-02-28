@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title mb-0">Renewal History for {{ $vehicle->registration_no }}</h4>
                <a href="{{ route('admin.vehicle.index') }}" class="btn btn-primary btn-md">← Back</a>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mt-3 mb-1">
                    <table class="table align-middle">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>#</th>
                                <th>Renewal Type</th>
                                {{-- <th>Issue Date</th> --}}
                                <th>Expiry Date</th>
                                <th>Renewal Status</th>
                                <th>Payment Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($renewals->count())
                                @foreach($renewals as $key => $renewal)
                                    <tr>
                                        <td>{{ $renewals->firstItem() + $loop->index }}</td>
                                        <td>{{ $renewal->renewalType->name ?? '—' }}</td>
                                        <td>{{ $renewal->renewable->expiry_date_bs ?? '-' }}</td>
                                        {{-- <td>{{ $renewal->start_date }}</td>
                                        <td>{{ $renewal->expiry_date }}</td> --}}
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

                                        <td>
                                            @if($renewal)
                                                <span class="badge bg-{{ $renewal->is_paid == 1 ? 'success' : 'danger' }}">
                                                    {{ ucfirst($renewal->is_paid == 1 ? 'Paid' : 'Unpaid' ) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $renewal->remarks ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <div class="noresult text-center" style="display: none">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a"
                                            style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">No matching records found.</p>
                                </div>
                            @endif
                        </tbody>

                        @if ($renewals->hasPages())
    <tr>
        <td colspan="6">
            <div class="d-flex justify-content-end">
                {{ $renewals->links('pagination::bootstrap-5') }}
            </div>
        </td>
    </tr>
@endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
