@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Customer</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Customer</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->


    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Customer List</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                         <div class="listjs-table" id="customerList">
                            <!-- Filters -->
                            <div class="row g-3">
                                <div class="col-xxl-3 col-sm-12">
                                    <label>Customer Name</label>
                                    <div class="search-box">
                                        <input type="text" id="customer_name"  class="form-control" placeholder="Search for customer">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-sm-12">
                                    <label>Email</label>
                                    <div class="search-box">
                                        <input type="text" id="email"  class="form-control" placeholder="Search for email">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-sm-12">
                                    <label>Phone Number</label>
                                    <div class="search-box">
                                        <input type="text" id="phone"  class="form-control" placeholder="Search for phone number">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-2 col-sm-4">
                                    <label>Status</label>
                                    <select class="form-select" id="idStatus">
                                        <option value="all" selected>All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Customer Name</th>
                                            <th>Email Address</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="customerTableBody">
                                        @include('customer.partials.table', ['customers' => $customers])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- customer -->
        </div> 
        <!-- end col -->
    </div>
    <!-- customer -->
@endsection


@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>

    <script>
        // Function to fetch filtered data
        function SearchData(page = 1) {
            // Grab all filter values
            const customer = document.getElementById('customer_name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const status = document.getElementById('idStatus').value;

            const params = { customer, email, phone, status, page };

            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = `<tr><td colspan="6" class="text-center p-4">Loading...</td></tr>`;

            fetch(`{{ route('admin.customer.index') }}?${new URLSearchParams(params)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = data.html;

                    // Re-bind pagination links
                    document.querySelectorAll('.pagination a').forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            const page = new URL(this.href).searchParams.get('page');
                            SearchData(page);
                        });
                    });
                })
                .catch(() => {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>`;
                });
        }

        // Optionally, trigger on input change (for instant search)
        document.querySelectorAll('#customer_name,#email,#phone').forEach(el=>{
            el.addEventListener('input', () => SearchData());
        });

        document.getElementById('idStatus').addEventListener('change', () => SearchData());
    </script>
@endpush