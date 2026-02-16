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
                    {{-- Vehicle Info --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Vehicle No</label>
                            <input type="text" class="form-control" name="registration_no" autocomplete="off"
                                value="{{ $vehicle->registration_no }}" readonly/>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Owner Name</label>
                            <input type="text" class="form-control" name="first_name" autocomplete="off"
                                value="{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}" readonly/>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" autocomplete="off"
                                value="{{ $customer->email }}" readonly/>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="phone_no" autocomplete="off"
                                value="{{ $customer->phone }}" readonly/>
                        </div>
                    </div>

                    {{-- Renewal Form --}}
                    <form method="POST" action="{{ route('admin.vehicle.update-renewal', $vehicle->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                        <div class="row mt-3 pt-3" style="border-top: 1px solid var(--vz-border-color);">

                            @foreach($renewalTypes as $slug => $label)
                                @if($slug == 'license') @continue @endif

                                <div class="col-md-6">
                                    <div class="card border card-border-light mb-3 p-3">

                                        {{-- Checkbox --}}
                                        <div class="form-check mb-3">
                                            <input class="form-check-input renewal-checkbox" type="checkbox" 
                                                name="renewals[]" value="{{ $slug }}" id="{{ $slug }}"
                                                {{ in_array($slug, old('renewals', [])) ? 'checked' : '' }}>

                                            <label class="form-check-label fw-bold" for="{{ $slug }}">
                                                {{ $label }}
                                            </label>
                                        </div>

                                        {{-- Renewal Fields --}}
                                        <div class="renewal-fields" style="{{ in_array($slug, old('renewals', [])) ? 'display:block;' : 'display:none;' }}">
                                            <div class="row">

                                                @php
                                                    $fields = $renewalFields[$slug] ?? [];
                                                @endphp

                                                @foreach($fields as $field)
                                                    <div class="col-md-4 mb-3">
                                                        <label>{{ $field['label'] }}</label>

                                                        @if($field['type'] === 'select')
                                                            <select name="{{ $slug }}[{{ $field['name'] }}]" 
                                                                    class="form-select form-control @error($slug.'.'.$field['name']) is-invalid @enderror">
                                                                <option value="">Select {{ $field['label'] }}</option>

                                                                @foreach($field['options'] as $value => $optionLabel)
                                                                    <option value="{{ $value }}" 
                                                                        {{ old($slug.'.'.$field['name']) == $value ? 'selected' : '' }}>
                                                                        {{ $optionLabel }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        @elseif($field['type'] === 'date')
                                                            <input type="text" 
                                                                class="form-control nepali-date @error($slug.'.'.$field['name']) is-invalid @enderror" 
                                                                name="{{ $slug }}[{{ $field['name'] }}]" 
                                                                value="{{ old($slug.'.'.$field['name']) }}"
                                                                placeholder="Select Date"
                                                                readonly>
                                                        @else
                                                            <input type="text" 
                                                                class="form-control @error($slug.'.'.$field['name']) is-invalid @enderror" 
                                                                name="{{ $slug }}[{{ $field['name'] }}]" 
                                                                value="{{ old($slug.'.'.$field['name']) }}">
                                                        @endif

                                                        @error($slug.'.'.$field['name'])
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endforeach

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

        // Show/hide fields dynamically
        $('.renewal-checkbox').change(function () {
            const wrapper = $(this).closest('.card').find('.renewal-fields');
            if ($(this).is(':checked')) wrapper.slideDown();
            else wrapper.slideUp();
        });

        // Initialize Nepali Datepicker
        $('.nepali-date').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpYearCount: 10,
            readOnlyInput: true,
            disableDaysAfter: 5
        });

    });
    </script>
@endpush