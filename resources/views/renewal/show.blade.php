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
                    <label>Issue Date</label>
                    <input type="text" id="nepali-datepicker" class="form-control" placeholder="Select Nepali Date"/>

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
                            <input type="text" class="form-control" id="nepali-datepicker" name="issue_date" placeholder="Select Nepali Date"/>
                            <!-- <input type="text" class="form-control" id="nepali-datepicker" name="issue_date" placeholder="Select Issue Date"/> -->
                        </div>
                        <div class="mb-3">
                            <label>Last Renewed At</label>
                            <input type="text" class="form-control" id="nepali-datepicker1" name="last_renewed_at" placeholder="Select Last Renew Date"/>
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" class="form-control" id="nepali-datepicker2" name="expiry_date" placeholder="Select Expiry Date"/>
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
    <div class="modal fade" id="bluebookModal" tabindex="-1" aria-labelledby="bluebookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="bluebookForm" method="POST" action="{{ route('admin.renewal.store') }}">
                @csrf
                <input type="hidden" name="renewal_type_id" value="">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="type" value="bluebook">

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
                            <input type="text" class="form-control" id="nepali-datepicker3" name="issue_date" placeholder="Select Issue Date"/>
                        </div>
                        <div class="mb-3">
                            <label>Last Renewed At</label>
                            <input type="text" class="form-control" id="nepali-datepicker1" name="last_renewed_at" placeholder="Select Last Renew Date"/>
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="text" class="form-control" id="nepali-datepicker2" name="expiry_date" placeholder="Select Expiry Date"/>
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
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".renewal-checkbox").forEach(function (checkbox) {
                checkbox.addEventListener("change", function () {
                    if (this.checked) {
                        let type = this.dataset.type.toLowerCase();
                        let modalId = type + "Modal";
                        let modal = new bootstrap.Modal(document.getElementById(modalId));
                        modal.show();

                        // Set the correct renewal_type_id in hidden field
                        document.querySelector(`#${modalId} input[name="renewal_type_id"]`).value = this.dataset.typeId;
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#bluebookModal').on('shown.bs.modal', function () {
                // Initialize datepickers only once
                let $issue = $('#nepali-datepicker');
                let $lastRenew = $('#nepali-datepicker1');
                let $expiry = $('#nepali-datepicker2');

                if (!$issue.hasClass('ndp-initialized')) {
                    $issue.NepaliDatePicker();
                }
                if (!$lastRenew.hasClass('ndp-initialized')) {
                    $lastRenew.NepaliDatePicker();
                }
                if (!$expiry.hasClass('ndp-initialized')) {
                    $expiry.NepaliDatePicker();
                }
            });
        });
    </script>

@endpush

