@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Bluebook</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Bluebook Renewal List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Vehicle List</h4>
                </div>

                <div class="card-body">
                    <div class="live-preview">
                        <div class="listjs-table" id="customerList">
                            <!-- Filters -->
                            <div class="row g-3">
                                <div class="col-xxl-2 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="Search for customer">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="Search for registration no">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="Last Expiry Date">
                                        <i class="ri-calendar-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="New Expiry Date">
                                        <i class="ri-calendar-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-4">
                                    <select class="form-select" id="idStatus">
                                        <option value="all" selected>All</option>
                                        <option value="unpaid">Unpaid</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary w-100" onclick="SearchData();">
                                        <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Vehicle Type</th>
                                            <th>Registration No</th>
                                            <th>Last Expiry Date</th>
                                            <th>New Expiry Date</th>
                                            <th>Renewal</th>
                                            <th>Payment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="renewalTableBody">
                                        @include('vehicle.partials.table', ['vehicles' => $vehicles])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>

<script>
    // AJAX Filter + Pagination
    function SearchData(page = 1) {
        const customer = document.querySelector('input[placeholder="Search for customer"]').value;
        const registration_no = document.querySelector('input[placeholder="Search for registration no"]').value;
        const last_expiry_date = document.querySelector('input[placeholder="Last Expiry Date"]').value;
        const new_expiry_date = document.querySelector('input[placeholder="New Expiry Date"]').value;
        const status = document.getElementById('idStatus').value;

        const params = { customer, registration_no, last_expiry_date, new_expiry_date, status, page };

        const tbody = document.getElementById('renewalTableBody');
        tbody.innerHTML = `<tr><td colspan="9" class="text-center p-4">Loading...</td></tr>`;

        fetch(`{{ route('admin.vehicle.index') }}?${new URLSearchParams(params)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = data.html;

            // Re-bind pagination links
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = new URL(this.href).searchParams.get('page');
                    SearchData(page);
                });
            });
        })
        .catch(() => {
            tbody.innerHTML = `<tr><td colspan="9" class="text-center text-danger">Error loading data</td></tr>`;
        });
    }
</script>
@endpush
