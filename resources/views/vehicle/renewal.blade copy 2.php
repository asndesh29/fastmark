@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <h4 class="card-title mb-0">Vehicle Info</h4>
                    <a href="{{ route('admin.vehicle.index') }}" class="btn btn-primary btn-md">← Back</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Vehicle No</label>
                                <input type="text" class="form-control" name="registration_no"
                                    placeholder="Select Expiry Date" autocomplete="off"
                                    value="{{ $vehicle->registration_no }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Owner Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="Select Expiry Date"
                                    autocomplete="off"
                                    value="{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="text" 
                                    class="form-control" 
                                    name="email"
                                    autocomplete="off"
                                    value="{{ $customer->email }}" 
                                />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Phone Number</label>
                                <input type="text" 
                                    class="form-control" 
                                    name="phone_no"
                                    autocomplete="off"
                                    value="{{ $customer->phone }}" 
                                />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                <select class="form-select mb-3" name="vehicle_type" data-placeholder="Select Type" title="Select Type"
                                data-choices name="choices-single-default" id="choices-single-default">
                                    @foreach ($vehicle_types as $vt)
                                        <option value="{{ $vt->id }}"
                                            {{ $vehicle->vehicle_type_id == $vt->id ? 'selected' : '' }}>
                                            {{ $vt->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="vehicle_category" class="form-label">Vehicle Category</label>
                                <select class="form-select mb-3" name="vehicle_category" data-placeholder="Select Category" title="Select Category"
                                data-choices name="choices-single-default" id="choices-single-default">
                                    @foreach ($vehicle_categories as $key => $vc)
                                        <option value="{{ $vc->id }}"
                                            {{ $vehicle->vehicle_category_id == $vc->id ? 'selected' : '' }}>
                                            {{ $vc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.vehicle.update-renewal', $vehicle->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        
                        <div class="row mt-3 pt-3" style="border-top: 1px solid var(--vz-border-color);">
                            @foreach($renewalTypes as $slug => $label)
                                
                                @if ($slug == 'license')
                                    @continue
                                @endif

                                <div class="col-md-6">
                                    <div class="card border card-border-light mb-3 p-3">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input renewal-checkbox" type="checkbox" name="renewals[]" value="{{ $slug }}"
                                                id="{{ $slug }}">

                                            <label class="form-check-label fw-bold" for="{{ $slug }}">
                                                {{ $label }}
                                            </label>
                                        </div>

                                        <div class="renewal-fields" style="display: none;">
                                            <div class="row">
                                                @if ($slug == 'insurance')
                                                    <div class="col-md-4 mb-3">
                                                        <label>Insurance Provider</label>
                                                        <select 
                                                            name="insurance[provider_id]" 
                                                            class="form-control @error('insurance.provider_id') is-invalid @enderror">

                                                            <option value="">Select Provider</option>

                                                            @foreach($insuranceProviders as $provider)
                                                                <option value="{{ $provider->id }}"
                                                                    {{ old('insurance.provider_id') == $provider->id ? 'selected' : '' }}>
                                                                    {{ $provider->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('insurance.provider_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Issue Date</label>
                                                        <input type="text"
                                                            class="form-control nepali-date 
                                                            @error('insurance.issue_date_bs') is-invalid @enderror"
                                                            name="insurance[issue_date_bs]"
                                                            value="{{ old('insurance.issue_date_bs') }}">

                                                        @error('insurance.issue_date_bs')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Expiry Date</label>
                                                        <input type="text"
                                                            class="form-control nepali-date 
                                                            @error('insurance.expiry_date_bs') is-invalid @enderror"
                                                            name="insurance[expiry_date_bs]"
                                                            value="{{ old('insurance.expiry_date_bs') }}">

                                                        @error('insurance.expiry_date_bs')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Insurance Type</label>
                                                        @php
                                                            $types = ['general', 'third', 'partial'];
                                                        @endphp
                                                        <select 
                                                            name="insurance[insurance_type]" 
                                                            class="form-control @error('insurance.insurance_type') is-invalid @enderror">

                                                            <option value="">Select Insurance Type</option>

                                                            @foreach($types as $type)
                                                                <option value="{{ $type }}"
                                                                    {{ old('insurance.insurance_type') == $type ? 'selected' : '' }}>
                                                                    {{ ucfirst($type) }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('insurance.insurance_type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label>Policy Number</label>
                                                        <input type="text"
                                                            class="form-control"
                                                            name="insurance[policy_number]"
                                                            placeholder="Enter Policy Number"
                                                            autocomplete="off">
                                                    </div>
                                                @endif
                                            
                                                @if ($slug != 'insurance')
                                                    <div class="col-md-4">
                                                        <label>Expiry Date</label>
                                                        <input type="text"
                                                            class="form-control nepali-date 
                                                            @error($slug.'.expiry_date_bs') is-invalid @enderror"
                                                            name="{{ $slug }}[expiry_date_bs]"
                                                            value="{{ old($slug.'.expiry_date_bs') }}"
                                                            placeholder="Select Expiry Date"
                                                            readonly>

                                                        @error($slug.'.expiry_date_bs')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endif

                                                <div class="col-md-4">
                                                    <label>Status</label>
                                                    <select name="{{ $slug }}[payment_status]" class="form-control">
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
                                </div>
                            @endforeach
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-success">
                                Renew Selected
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                readOnlyInput: true,
                disableDaysAfter: 5
            });

            // Form validation on submit
            $('form').submit(function (e) {
                if ($('.renewal-checkbox:checked').length === 0) {
                    alert('Please select at least one renewal type.');
                    e.preventDefault(); // stop form submission
                }
            });

            @if(old('renewals'))
                @foreach(old('renewals') as $oldSlug)
                    $('#{{ $oldSlug }}').prop('checked', true)
                        .closest('.card')
                        .find('.renewal-fields')
                        .show();
                @endforeach
            @endif
        });
    </script>
@endpush