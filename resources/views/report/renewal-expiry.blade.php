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

    <!-- Vehicle List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Filter by</h4>
                </div>

                <div class="card-body">
                    <div class="live-preview">
                        <div class="listjs-table" id="customerList">
                            <!-- Filters -->
                            <form method="GET" action="{{ route('admin.report.renewals.expiry') }}">
                                <div class="row g-3">
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
                                        <input type="text"
                                            id="from_date"
                                            name="from_date"
                                            class="form-control nepali-date"
                                            value="{{ request('from_date') }}"
                                            placeholder="YYYY-MM-DD"
                                            autocomplete="off"
                                            autocorrect="off"
                                            autocapitalize="off"
                                            spellcheck="false"
                                            readonly>
                                    </div>

                                    <div class="col-xxl-2 col-sm-4">
                                        <label>To Date</label>
                                       <input type="text"
                                            id="to_date"
                                            name="to_date"
                                            class="form-control nepali-date"
                                            value="{{ request('to_date') }}"
                                            placeholder="YYYY-MM-DD"
                                            autocomplete="off"
                                            autocorrect="off"
                                            autocapitalize="off"
                                            spellcheck="false"
                                            readonly>
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
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
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
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <!-- Table -->
                        <div class="table-responsive table-card">
                            <table class="table align-middle">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Vehicle</th>
                                        <th>Renewal Type</th>
                                        <th>Start Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Alert</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($renewals as $key => $renewal)
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $expiry = \Carbon\Carbon::parse($renewal->expiry_date_ad)->startOfDay();
                                            $daysLeft = $today->diffInDays($expiry, false);
                                        @endphp

                                        <tr class="
                                            @if($daysLeft < 0) table-danger
                                            @elseif($daysLeft <= 7) table-warning
                                            @endif
                                        ">
                                            <td>{{ $key + $renewals->firstItem() }}</td>
                                            <td>{{ $renewal->vehicle?->registration_no }}</td>
                                            <td>{{ $renewal->renewalType?->name }}</td>
                                            <td>{{ $renewal->start_date_bs }}</td>
                                            <td>{{ $renewal->expiry_date_bs }}</td>
                                            <td>{{ ucfirst($renewal->status) }}</td>
                                            <td>
                                                @if($daysLeft < 0)
                                                    <span class="badge bg-danger">Expired</span>
                                                @elseif($daysLeft <= 7)
                                                    <span class="badge bg-warning text-dark">
                                                        Expiring in {{ $daysLeft }} days
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">Valid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No records found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- {{ $renewals->withQueryString()->links() }} --}}

                            @if ($renewals->hasPages())
                                <tr>
                                    <td colspan="10">
                                        <div class="d-flex justify-content-end">
                                            {!! $renewals->links('pagination::bootstrap-5') !!}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- customer -->
        </div> 
        <!-- end col -->
    </div>
@endsection

@push('script_2')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var nepaliDates = document.querySelectorAll(".nepali-date");

            nepaliDates.forEach(function (input) {
                input.nepaliDatePicker({
                    ndpYear: true,
                    ndpMonth: true,
                    ndpYearCount: 10
                });
            });
        });
    </script>
@endpush
