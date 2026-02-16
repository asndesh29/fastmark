@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Customer</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.customer.store') }}" method="POST">
                @csrf

                {{-- ================= CUSTOMER INFO ================= --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer Info</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationDefault01" class="form-label">First name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}"
                                        placeholder="Ex: First Name">

                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationDefault02" class="form-label">Middle name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                        id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                                        placeholder="Ex: Middle Name">

                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="validationDefault02" class="form-label">Last name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}"
                                        placeholder="Ex: Last Name">

                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                        name="phone" value="{{ old('phone') }}" placeholder="Ex: +977-">

                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                        name="email" value="{{ old('email') }}" placeholder="Ex: john@doe.com">

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ================= VEHICLE INFO ================= --}}
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="card-title">Vehicle Info</h4>
                        <button type="button" class="btn btn-primary btn-sm" id="add-vehicle-btn">
                            Add New Vehicle
                        </button>
                    </div>

                    <div class="card-body">
                        <div id="vehicle-container">

                            @php
                                $oldVehicleTypes = old('vehicle_types', [null]);
                            @endphp

                            @foreach ($oldVehicleTypes as $index => $oldVehicleType)

                                <div class="vehicle-row border rounded p-3 mb-4" style="background:#f8f9fc">

                                    <div class="row">

                                        {{-- Vehicle Category --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Vehicle Category</label>
                                            <select name="vehicle_categories[]"
                                                class="form-select @error('vehicle_categories.' . $index) is-invalid @enderror"
                                                required>
                                                @foreach ($vehicle_categories as $vc)
                                                    <option value="{{ $vc->id }}" {{ old('vehicle_categories.' . $index) == $vc->id ? 'selected' : '' }}>
                                                        {{ $vc->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Vehicle Type --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Vehicle Type</label>
                                            <select name="vehicle_types[]"
                                                class="form-select @error('vehicle_types.' . $index) is-invalid @enderror"
                                                required>
                                                @foreach ($vehicle_types as $vt)
                                                    <option value="{{ $vt->id }}" {{ $oldVehicleType == $vt->id ? 'selected' : '' }}>
                                                        {{ $vt->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Registration No --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Registration No</label>
                                            <input type="text" name="registration_no[]"
                                                value="{{ old('registration_no.' . $index) }}"
                                                class="form-control @error('registration_no.' . $index) is-invalid @enderror">

                                            @error('registration_no.' . $index)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Permit No --}}
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label">Permit No</label>
                                            <input type="text" name="permit_no[]" value="{{ old('permit_no.' . $index) }}"
                                                class="form-control @error('permit_no.' . $index) is-invalid @enderror">

                                            @error('permit_no.' . $index)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Chassis --}}
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label">Chassis No</label>
                                            <input type="text" name="chassis_no[]" value="{{ old('chassis_no.' . $index) }}"
                                                class="form-control">
                                        </div>

                                        {{-- Engine --}}
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label">Engine No</label>
                                            <input type="text" name="engine_no[]" value="{{ old('engine_no.' . $index) }}"
                                                class="form-control">
                                        </div>

                                        {{-- Engine CC --}}
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label">Engine CC</label>
                                            <input type="text" name="engine_cc[]" value="{{ old('engine_cc.' . $index) }}"
                                                class="form-control">
                                        </div>

                                        {{-- Capacity --}}
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label">Capacity</label>
                                            <input type="text" name="capacity[]" value="{{ old('capacity.' . $index) }}"
                                                class="form-control">
                                        </div>

                                        {{-- Remove Button --}}
                                        <div class="col-12 text-end mt-3">
                                            <button type="button" class="btn btn-danger btn-sm remove-vehicle-btn">
                                                Remove
                                            </button>
                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                Save Customer
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
        <!-- end col -->
    </div>
    <!-- customer -->
@endsection

@push('script_2')

    <script>
        function updateRemoveButtons() {
            let rows = document.querySelectorAll('.vehicle-row');
            let buttons = document.querySelectorAll('.remove-vehicle-btn');

            if (rows.length === 1) {
                // hide the only remove button
                buttons.forEach(btn => btn.style.display = 'none');
            } else {
                // show all remove buttons
                buttons.forEach(btn => btn.style.display = 'inline-block');
            }
        }

        document.getElementById('add-vehicle-btn').addEventListener('click', function () {
            let container = document.getElementById('vehicle-container');
            let newRow = container.querySelector('.vehicle-row').cloneNode(true);

            // clear input values in the new row
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            // re-attach remove button event
            newRow.querySelector('.remove-vehicle-btn').addEventListener('click', function () {
                newRow.remove();
                updateRemoveButtons();
            });

            container.appendChild(newRow);
            updateRemoveButtons();
        });

        // attach remove event for the first row
        document.querySelectorAll('.remove-vehicle-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                btn.closest('.vehicle-row').remove();
                updateRemoveButtons();
            });
        });

        // initial check
        updateRemoveButtons();



        window.onload = function () {
            var mainInput = document.getElementById("nepali-datepicker");
            mainInput.NepaliDatePicker();
        };
    </script>
@endpush