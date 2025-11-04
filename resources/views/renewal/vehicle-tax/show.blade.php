@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title mb-0">Renewal History for {{ $vehicletax->vehicle->registration_no }}</h4>
                <a href="{{ route('admin.renewal.vehicle-tax.index') }}" class="btn btn-primary btn-md">← Back</a>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mt-3 mb-1">
                    <table class="table align-middle">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Renewal Type</th>
                                <th>Issue Date</th>
                                <th>Last Expiry Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($vehicletax->vehicle->renewals->count())
                                @foreach($vehicletax->vehicle->renewals as $renewal)
                                    <tr>
                                        <td>{{ $renewal->renewalType->name ?? '—' }}</td>
                                        <td>{{ $vehicletax->issue_date }}</td>
                                        <td>{{ $renewal->start_date }}</td>
                                        <td>{{ $renewal->expiry_date }}</td>
                                        <td>
                                            <span class="badge bg-{{ $renewal->status == 'paid' ? 'success' : 'danger' }}">
                                                {{ ucfirst($renewal->status) }}
                                            </span>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
