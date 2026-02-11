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

        <div class="row">
            <div class="col-md-6">

                <!-- Bluebook -->
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="renewals[]" value="bluebook">
        <label class="form-check-label">Bluebook</label>
    </div>
    <input type="date" name="bluebook_expiry" class="form-control mb-3">

                @php
                    $renewals = [
                        'bluebook_expiry' => 'Bluebook',
                        'jach_pass_expiry' => 'Jach Pass',
                        'insurance_expiry' => 'Insurance',
                        'pollution_expiry' => 'Pollution',
                        'road_permit_expiry' => 'Road Permit',
                        'vehicle_tax_expiry' => 'Vehicle Tax',
                    ];
                @endphp

                @foreach($renewals as $field => $label)
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">{{ $label }}</label>
                        <div class="col-sm-8">
                            <input type="date" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror"
                                value="{{ old($field, $vehicle->$field) }}">

                            @error($field)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <div class="text-end mt-4">
    <button type="submit" class="btn btn-success">
        Update Renewals
    </button>
</div>
    </form>

@endsection