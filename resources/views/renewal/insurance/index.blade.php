@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Insurance</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Insurance Renewal List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Insurance Renewal List</h4>
                </div>

                <div class="card-body">
                    <div class="live-preview">
                        <div class="listjs-table" id="customerList">
                            <!-- Filters -->
                            <div class="row g-3">
                                <div class="col-xxl-3 col-sm-12">
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

                                {{-- <div class="col-xxl-2 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" class="form-control"
                                               placeholder="Last Expiry Date">
                                        <i class="ri-calendar-line search-icon"></i>
                                    </div>
                                </div> --}}

                                <div class="col-xxl-3 col-sm-4">
                                    <select id="vehicle_type_id" name="vehicle_type_id" class="form-select">
                                        <option value="">Select Vehicle Type</option>
                                        @foreach($vehicle_types as $type)
                                            <option value="{{ $type->id }}" {{ request('vehicle_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xxl-3 col-sm-12">
                                    <div class="search-box">
                                        <input type="text" id="expiry_date_bs" name="expiry_date_bs"
                                            class="form-control nepali-date" value="{{ request('expiry_date_bs') }}"
                                            placeholder="YYYY-MM-DD" autocomplete="off" autocorrect="off"
                                            autocapitalize="off" spellcheck="false" />
                                        <i class="ri-calendar-line search-icon"></i>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-sm-4">
                                    <select class="form-select" id="payment_status">
                                        <option value="all" selected>Select Payment Status</option>
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
                                            <th>Insurance Provider</th>
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
                                        @include('renewal.insurance.partials.table', ['renewal_lists' => $renewal_lists])
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insurance Modal -->
    <div class="modal fade" id="insuranceModal" tabindex="-1" aria-labelledby="insuranceModal"
            aria-hidden="true">
        <div class="modal-dialog">
            <form id="insuranceForm" method="POST" action="{{ route('admin.renewal.insurance.store') }}">
                @csrf
                <input type="hidden" name="vehicle_id">
                <input type="hidden" name="renewable_type" value="insurance">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Insurance Renewal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Insurance Provider</label>
                            <select name="provider_id" id="provider_id" class="form-select">
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Insurance Type</label>
                            <select class="form-select" name="insurance_type">
                                <option value="general">General</option>
                                <option value="third">Third</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Policy Number</label>
                            <input type="text" class="form-control" name="policy_number"
                                    placeholder="Enter Policy Number" autocomplete="off"/>
                        </div>

                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" 
                                class="form-control nepali-date" name="expiry_date_bs"
                                    placeholder="Select Expiry Date" 
                                    autocomplete="off"
                                    readonly />
                        </div>
                        
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" name="payment_status">
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" name="renewal_charge" placeholder="Enter amount">
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

            /* ================================
               1. Initialize Filter Date (Outside Modal)
               ================================ */
            const filterInput = $('#expiry_date_bs');
            if (!filterInput.hasClass('ndp-initialized')) {
                filterInput.NepaliDatePicker({
                    ndpYear: true,
                    ndpMonth: true,
                    ndpYearCount: 20
                });
                filterInput.addClass('ndp-initialized');
            }


            /* ================================
               2. Initialize Datepicker Inside Modal
               ================================ */
            const modal = $('#insuranceModal');
            modal.on('shown.bs.modal', function () {
                $(this).find('.nepali-date').each(function () {
                    if (!$(this).hasClass('ndp-initialized')) {
                        $(this).NepaliDatePicker({
                            ndpYear: true,
                            ndpMonth: true,
                            ndpYearCount: 20,
                            container: '#insuranceModal'
                        });
                        $(this).addClass('ndp-initialized');
                    }
                });
            });


            /* ================================
               3. Modal Vehicle ID Setup
               ================================ */
            const vehicleInput = modal.find('input[name="vehicle_id"]');
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.addBtn');
                if (btn) {
                    vehicleInput.val(btn.getAttribute('data-vehicle-id'));
                }
            });


            /* ================================
               4. Form Validation
               ================================ */
            const form = document.getElementById('insuranceForm');

            form.addEventListener('submit', function (e) {
                clearErrorMessages();

                const expiryDate = form.querySelector('input[name="expiry_date_bs"]');
                let hasError = false;

                if (!expiryDate.value) {
                    showError(expiryDate, 'Expiry Date is required.');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                }
            });

            function showError(input, message) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('invalid-feedback');
                errorDiv.textContent = message;
                input.parentElement.appendChild(errorDiv);
            }

            function clearErrorMessages() {
                const errorMessages = form.querySelectorAll('.invalid-feedback');
                errorMessages.forEach(function (error) {
                    error.remove();
                });

                const inputs = form.querySelectorAll('.form-control');
                inputs.forEach(function (input) {
                    input.classList.remove('is-invalid');
                });
            }

        });


        /* ================================
           5. AJAX Filter + Pagination
           ================================ */
        function SearchData(page = 1) {
            const invoice = document.querySelector('input[placeholder="Search for invoice"]').value;
            const customer = document.querySelector('input[placeholder="Search for customer"]').value;
            const registration_no = document.querySelector('input[placeholder="Search for registration no"]').value;
            const vehicle_type_id = document.getElementById('vehicle_type_id') ? document.getElementById('vehicle_type_id').value : '';
            const expiry_date_bs = document.getElementById('expiry_date_bs').value;
            const status = document.getElementById('payment_status').value;

            const params = {
                invoice,
                customer,
                registration_no,
                vehicle_type_id,
                expiry_date_bs,
                status,
                page
            };

            const tbody = document.getElementById('renewalTableBody');
            tbody.innerHTML = `<tr>
                <td colspan="10" class="text-center p-4">Loading...</td>
            </tr>`;

            fetch(`{{ route('admin.renewal.insurance.index') }}?${new URLSearchParams(params)}`, {
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
                    tbody.innerHTML = `<tr>
                        <td colspan="10" class="text-center text-danger">
                            Error loading data
                        </td>
                    </tr>`;
                });
        }
    </script>
@endpush