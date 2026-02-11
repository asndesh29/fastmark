@extends('layouts.app')

@section('content')
    {{-- <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title mb-0">Vehicle Info</h4>
                    <a href="#" class="btn btn-primary btn-md">← Back</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label>Vehicle No</label>
                                <input type="text" class="form-control" name="registration_no"
                                    placeholder="Select Expiry Date" autocomplete="off"
                                    value="{{ $vehicle->registration_no }}" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label>Owner Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="Select Expiry Date"
                                    autocomplete="off"
                                    value="{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Bluebook</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="nameInput" placeholder="Enter your name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Jach Pass</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="nameInput" placeholder="Enter your name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Insurance</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="nameInput" placeholder="Enter your name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Pollution</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="nameInput" placeholder="Enter your name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Road Permit</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="nameInput" placeholder="Enter your name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Vehicle Tax</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" id="nameInput" placeholder="Enter your name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    <form method="POST" action="{{ route('admin.vehicle.update-renewal', $vehicle->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

        @php
            $renewalTypes = [
                'bluebook' => 'Bluebook',
                'pollution' => 'Pollution',
                'vehicle-tax' => 'Vehicle Tax',
            ];
        @endphp

        @foreach($renewalTypes as $slug => $label)
            <div class="card mb-3 p-3">

                <div class="form-check mb-3">
                    <input class="form-check-input renewal-checkbox" type="checkbox" name="renewals[]" value="{{ $slug }}"
                        id="{{ $slug }}">

                    <label class="form-check-label fw-bold" for="{{ $slug }}">
                        {{ $label }}
                    </label>
                </div>

                {{-- Container to show/hide --}}
                <div class="renewal-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Last Expiry Date</label>
                            <input type="text" class="form-control nepali-date @error('last_expiry_date') is-invalid @enderror"
                                name="{{ $slug }}[last_expiry_date]" placeholder="Select Expiry Date" autocomplete="off" />
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select name="{{ $slug }}[status]" class="form-control">
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Remarks</label>
                            <input type="text" name="{{ $slug }}[remarks]" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        <div class="text-end">
            <button type="submit" class="btn btn-success">
                Renew Selected
            </button>
        </div>
    </form>



@endsection


@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Show/hide fields on checkbox change
            $('.renewal-checkbox').change(function () {
                const wrapper = $(this).closest('.card').find('.renewal-fields');
                if ($(this).is(':checked')) {
                    wrapper.slideDown(); // show with animation
                } else {
                    wrapper.slideUp();   // hide with animation
                }
            });

            // Initialize Nepali Datepicker only on visible fields
            $('.nepali-date').nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10,
                readOnlyInput: true
            });

            // Form validation on submit
            $('form').submit(function (e) {
                if ($('.renewal-checkbox:checked').length === 0) {
                    alert('Please select at least one renewal type.');
                    e.preventDefault(); // stop form submission
                }
            });
        });
    </script>
@endpush