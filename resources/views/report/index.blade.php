@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Vehicle Report</h4>

                {{-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Bluebook</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div> --}}
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
                            <form method="GET" action="{{ route('admin.report.index') }}">
                                <div class="row g-3">
                                    <div class="col-xxl-3 col-sm-12">
                                        <label>Vehicle Type</label>
                                        <select name="vehicle_type_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($vehicle_types as $type)
                                                <option value="{{ $type->id }}" {{ request('vehicle_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xxl-2 col-sm-12">
                                        <label>Vehicle Category</label>
                                        <select name="vehicle_category_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($vehicle_categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('vehicle_category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xxl-2 col-sm-4">
                                        <label>Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-xxl-2 col-sm-4">
                                        <label>From Date</label>
                                        <input type="text" id="from_date" name="from_date" class="form-control nepali-date"
                                            value="{{ request('from_date') }}" placeholder="YYYY-MM-DD" autocomplete="off"
                                            autocorrect="off" autocapitalize="off" spellcheck="false" readonly>
                                    </div>

                                    <div class="col-xxl-2 col-sm-4">
                                        <label>To Date</label>
                                        <input type="text" id="to_date" name="to_date" class="form-control nepali-date"
                                            value="{{ request('to_date') }}" placeholder="YYYY-MM-DD" autocomplete="off"
                                            autocorrect="off" autocapitalize="off" spellcheck="false" readonly>
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
                    <h4 class="card-title mb-0 flex-grow-1">Vehicle List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.report.vehicles.export', request()->query()) }}">
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
                                        <th>#</th>
                                        <th>Registration No</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Owner</th>
                                        <th>Permit</th>
                                        <th>Engine No</th>
                                        <th>Chassis No</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('report.partials.table', ['vehicles' => $vehicles])
                                </tbody>
                            </table>
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