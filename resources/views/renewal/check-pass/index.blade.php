@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Vehicle Pass</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Vehicle Pass Renewal List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Vehicle Pass Renewal List</h4>
                </div>

                <div class="card-body">
                    <div class="live-preview">
                        <div class="listjs-table" id="customerList">
                            <!-- Filters -->
                            <div class="row g-3">
                                <div class="col-xxl-2 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="Search for invoice">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="Search for customer">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-sm-12">
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
                                            <th>Invoice No</th>
                                            <th>Customer</th>
                                            <th>Vehicle Type</th>
                                            <th>Registration No</th>
                                            <th>Expiry Date</th>
                                            {{-- <th>New Expiry Date</th> --}}
                                            <th>Renewal</th>
                                            <th>Payment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="renewalTableBody">
                                        @include('renewal.check-pass.partials.table', ['renewal_lists' => $renewal_lists])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Road Permit Modal -->
    <div class="modal fade" id="vehiclepassModal" tabindex="-1" aria-labelledby="vehiclepassModal"
            aria-hidden="true">
        <div class="modal-dialog">
            <form id="vehiclepassForm" method="POST" action="{{ route('admin.renewal.checkpass.store') }}">
                @csrf
                <input type="hidden" name="vehicle_id">
                <input type="hidden" name="renewable_type" value="vehicle-pass">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Jach Pass Renewal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date"
                                    placeholder="Select Issue Date" autocomplete="off"/>
                        </div> --}}
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" class="form-control nepali-date" name="expiry_date_bs"
                                    placeholder="Select Expiry Date" autocomplete="off"/>
                        </div>
                        {{-- <div class="mb-3">
                            <label>Tax Amount</label>
                            <input type="text" class="form-control" name="tax_amount" placeholder="Enter tax amount">
                        </div>

                        <div class="mb-3">
                            <label>Renewal Charge</label>
                            <input type="text" class="form-control" name="renewal_charge" placeholder="Enter renewal charge">
                        </div>

                        <div class="mb-3">
                            <label>Income Tax</label>
                            <input type="number" min="0" max="999999999.99" class="form-control" name="income_tax" placeholder="Enter income tax amount">
                        </div> --}}
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" name="payment_status">
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Renewal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dynamically set vehicle_id in modal
        const modal = document.getElementById('vehiclepassModal');
        const vehicleInput = modal.querySelector('input[name="vehicle_id"]');

         // Prevent modal from closing if form validation fails
        const form = document.getElementById('vehiclepassForm');
        form.addEventListener('submit', function (e) {
            // Clear any previous error messages
            clearErrorMessages();

            // Check if there are validation errors for required fields
            // const issueDate = form.querySelector('input[name="issue_date"]');
            const expiryDate = form.querySelector('input[name="expiry_date_bs"]');

            let hasError = false;

            // Validate Issue Date
            // if (!issueDate.value) {
            //     showError(issueDate, 'Issue Date is required.');
            //     hasError = true;
            // }

            // Validate Last Expiry Date
            if (!expiryDate.value) {
                showError(expiryDate, 'Expiry Date is required.');
                hasError = true;
            }

            // If there are errors, prevent form submission
            if (hasError) {
                e.preventDefault();
            }
        });

        // Show error message below the input field
        function showError(input, message) {
            input.classList.add('is-invalid');  // Adds Bootstrap invalid styling
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('invalid-feedback');
            errorDiv.textContent = message;
            input.parentElement.appendChild(errorDiv);  // Add error message below the input
        }

        // Clear all error messages
        function clearErrorMessages() {
            const errorMessages = form.querySelectorAll('.invalid-feedback');
            errorMessages.forEach(function(error) {
                error.remove();  // Remove error message
            });

            // Remove invalid class from all inputs
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(function(input) {
                input.classList.remove('is-invalid');
            });
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('.addBtn')) {
                const btn = e.target.closest('.addBtn');
                vehicleInput.value = btn.getAttribute('data-vehicle-id');
            }
        });

        // Initialize Nepali datepicker on page load
        document.querySelectorAll('.nepali-date').forEach(function(input) {
            if (!input.classList.contains('ndp-initialized')) {
                $(input).NepaliDatePicker({
                    container: '#vehiclepassModal'
                }).addClass('ndp-initialized');
            }
        });
    });

    // AJAX Filter + Pagination
    function SearchData(page = 1) {
        const invoice = document.querySelector('input[placeholder="Search for invoice"]').value;
        const customer = document.querySelector('input[placeholder="Search for customer"]').value;
        const registration_no = document.querySelector('input[placeholder="Search for registration no"]').value;
        const last_expiry_date = document.querySelector('input[placeholder="Last Expiry Date"]').value;
        const new_expiry_date = document.querySelector('input[placeholder="New Expiry Date"]').value;
        const status = document.getElementById('idStatus').value;

        const params = { invoice, customer, registration_no, last_expiry_date, new_expiry_date, status, page };

        const tbody = document.getElementById('renewalTableBody');
        tbody.innerHTML = `<tr><td colspan="10" class="text-center p-4">Loading...</td></tr>`;

        fetch(`{{ route('admin.renewal.checkpass.index') }}?${new URLSearchParams(params)}`, {
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
            tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger">Error loading data</td></tr>`;
        });
    }
</script>
@endpush
