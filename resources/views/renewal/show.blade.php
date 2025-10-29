@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal Detail</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Renewal List</h4>

                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-4">Use <code>table-card</code> class to show card-based table within a &lt;tbody&gt;.</p>

                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>S.No.</th>
                                <th>Renewal Type</th>
                                <th>Issue Date</th>
                                <th>Last Renewed Date</th>
                                <th>Expirty Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($renewal_types) > 0)
                                @foreach ($renewal_types as $index => $type)

                                    @php
                                        $renewal = $renewalsByType[$type->id] ?? null;
                                        $renewable = $renewal?->renewable;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input renewal-checkbox"
                                                type="checkbox"
                                                data-type="{{ $type->name }}"
                                                data-type-id="{{ $type->id }}"
                                                data-modal="{{ Str::camel($type->name) }}Modal"
                                                id="renewalCheckbox{{ $type->id }}">
                                            </div>
                                        </td>
                                        <td>{{ $type->name }}</td>
                                        <td>
                                            {{ $renewal ? ($renewable->issue_date ?? '-') : '-' }}
                                        </td>
                                        <td>
                                            {{ $renewable->last_renewed_at ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $renewal?->expiry_date ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $renewal?->status ?? 'Not Available' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- customer -->

    <!-- Bluebook Modal -->
    <div class="modal fade" id="bluebookModal" tabindex="-1" aria-labelledby="bluebookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="bluebookForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="bluebook">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Bluebook Renewal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Book Number</label>
                            <input type="text" class="form-control" name="book_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date" placeholder="Select Issue Date" autocomplete="off"/>
                            <!-- <input type="text" class="form-control" id="nepali-datepicker" name="issue_date" placeholder="Select Issue Date"/> -->
                        </div>
                        <div class="mb-3">
                            <label>Last Renewed At</label>
                            <input type="text" class="form-control nepali-date" name="last_renewed_at" placeholder="Select Last Renew Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" class="form-control nepali-date" name="expiry_date" placeholder="Select Expiry Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control select" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
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

    <!-- Road Permit Modal -->
    <div class="modal fade" id="roadPermitModal" tabindex="-1" aria-labelledby="roadPermitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="roadPermitForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="road_permit">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Road Permit Renewal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Permit Number</label>
                            <input type="text" class="form-control" name="permit_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date" placeholder="Select Issue Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" class="form-control nepali-date" name="expiry_date" placeholder="Select Expiry Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control select" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
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

    <!-- Pollution Modal -->
    <div class="modal fade" id="pollutionModal" tabindex="-1" aria-labelledby="pollutionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="pollutionForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="pollution">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add PollutionRenewal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Certificate Number</label>
                            <input type="text" class="form-control" name="certificate_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Last Renewed At</label>
                            <input type="text" class="form-control nepali-date" name="check_date" placeholder="Select Check Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date" placeholder="Select Issue Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control select" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
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

    <!-- Check Pass Modal -->
    <div class="modal fade" id="checkPassModal" tabindex="-1" aria-labelledby="checkPassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="checkPassForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="check_pass">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Check Pass</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <div class="mb-3">
                            <label>Book Number</label>
                            <input type="text" class="form-control" name="book_number" required>
                        </div> -->
                        <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date" placeholder="Select Issue Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Inspection Result</label>
                            <select class="form-control select" name="inspection_result">
                                <option value="pass">Pass</option>
                                <option value="fail">Fail</option>
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

    <!-- Insurance Modal -->
    <div class="modal fade" id="insuranceModal" tabindex="-1" aria-labelledby="insuranceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="insuranceForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="insurance">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Insurance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Select Insurance Provider</label>
                            <select name="provider_id" id="provider_id" class="form-control select">
                                @foreach ($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Policy Number</label>
                            <input type="text" class="form-control" name="policy_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date" placeholder="Select Issue Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="number" min="0" max="9999999999.00" step="0.01" class="form-control" name="amount" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control select" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div> -->
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

    <!-- Tax Modal -->
    <div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="taxForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="tax">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Tax</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Book Number</label>
                            <input type="text" class="form-control" name="book_number" required>
                        </div>
                        <div class="mb-3">
                            <label>Issue Date</label>
                            <input type="text" class="form-control nepali-date" name="issue_date" placeholder="Select Issue Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Last Renewed At</label>
                            <input type="text" class="form-control nepali-date" name="last_renewed_at" placeholder="Select Last Renew Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" class="form-control nepali-date" name="expiry_date" placeholder="Select Expiry Date" autocomplete="off"/>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control select" name="status">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
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
    $(document).ready(function () {
        $('.renewal-checkbox').on('change', function () {
            if (this.checked) {
                const $checkbox = $(this);
                const typeId = $checkbox.data('type-id');
                const modalId = `#${$checkbox.data('modal')}`;
                const $modal = $(modalId);

                // Set hidden input
                $modal.find('input[name="renewal_type_id"]').val(typeId);

                // Reset form (clear values)
                const $form = $modal.find('form');
                $form[0].reset();

                // Show modal
                const modalInstance = new bootstrap.Modal(document.querySelector(modalId));
                modalInstance.show();

                // Initialize Nepali datepickers
                $modal.find('.nepali-date').each(function () {
                    if (!$(this).hasClass('ndp-initialized')) {
                        $(this).NepaliDatePicker({
                            container: modalId
                        }).addClass('ndp-initialized');
                    }
                });
            }
        });

        // When modal is closed
        $('.modal').on('hidden.bs.modal', function () {
            const $modal = $(this);

            // Uncheck all checkboxes
            $('.renewal-checkbox').prop('checked', false);

            // Reset the form inside this modal
            const $form = $modal.find('form');
            if ($form.length > 0) {
                $form[0].reset();
            }

            // Optional: remove Nepali datepicker class if needed
            $modal.find('.nepali-date').removeClass('ndp-initialized');
        });
    });
</script>
@endpush