@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Renewal Expiry Report</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Report</a></li>
                    <li class="breadcrumb-item active">Renewal Expiry Report</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<!-- Filters -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Filter by</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.report.renewals.expiry') }}">
                    <div class="row g-3">
                        <div class="col-xxl-3 col-sm-4">
                            <label>Vehicle Registration No</label>
                            <input type="text" name="registration_no" class="form-control"
                                value="{{ request('registration_no') }}" placeholder="Enter Vehicle No">
                        </div>


                        <div class="col-xxl-3 col-sm-12">
                            <label>Renewal Type</label>
                            <select name="renewal_type_id" class="form-select">
                                <option value="">All</option>
                                @foreach($renewalTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ request('renewal_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <label>From Date</label>
                            <input type="text" name="from_date" class="form-control nepali-date"
                                   value="{{ request('from_date') }}" placeholder="YYYY-MM-DD">
                        </div>

                        <div class="col-xxl-2 col-sm-4">
                            <label>To Date</label>
                            <input type="text" name="to_date" class="form-control nepali-date"
                                   value="{{ request('to_date') }}" placeholder="YYYY-MM-DD">
                        </div>

                        <div class="col-auto pt-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Renewal Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Renewal Expiry List</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.report.renewals.expiry.export', request()->query()) }}">
                        <button type="button" class="btn btn-info">
                            <i class="ri-file-download-line align-bottom me-1"></i> Export
                        </button>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table align-middle table-bordered">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>S.No.</th>
                                <th>Vehicle No</th>
                                <th>Vehicle Code</th>
                                <th>Owner Name</th>
                                <th>Mobile</th>

                                @php
                                    // Determine which renewal types to show
                                    $typesToShow = request('renewal_type_id')
                                        ? $renewalTypes->where('id', request('renewal_type_id'))
                                        : $renewalTypes;

                                    $typesToShow = $typesToShow->where('slug', '!=', 'license');
                                @endphp

                                @foreach($typesToShow as $type)
                                    <th>{{ $type->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)

                                @php
                                    // Check if any renewal of this vehicle is expired
                                    $isRowExpired = false;

                                    foreach ($typesToShow as $type) {
                                        $renewalCheck = $vehicle->renewals
                                            ->where('renewal_type_id', $type->id)
                                            ->first();

                                        if ($renewalCheck && $renewalCheck->expiry_status === 'expired') {
                                            $isRowExpired = true;
                                            break;
                                        }
                                    }
                                @endphp

                                <tr class="{{ $isRowExpired ? 'table-danger' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vehicle->registration_no }}</td>
                                    <td>{{ substr($vehicle->registration_no, -4) }}</td>
                                    <td>{{ $vehicle->owner?->first_name }} {{ $vehicle->owner?->last_name }}</td>
                                    <td>{{ $vehicle->owner?->phone ?? '-' }}</td>

                                    @foreach($typesToShow as $type)
                                        @php
                                            $renewal = $vehicle->renewals
                                                ->where('renewal_type_id', $type->id)
                                                ->first();
                                        @endphp

                                        <td>
                                            {{ $renewal?->start_date_bs ?? '-' }}

                                            @if($renewal && $renewal->expiry_days !== null)

                                                @if($renewal->expiry_status === 'warning')
                                                    <br>
                                                    <span class="badge bg-warning">
                                                        {{ $renewal->expiry_days }} days left
                                                    </span>
                                                @endif

                                            @endif
                                        </td>
                                    @endforeach
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="{{ 5 + count($typesToShow) }}" class="text-center">
                                        No records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-2">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".nepali-date").forEach(function (input) {
            input.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10
            });
        });
    });
</script>
@endpush