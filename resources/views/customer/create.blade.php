@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Customer</h4>
            </div>
        </div>
    </div>

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
                            <div class="col-md-4 mb-3">
                                <label class="form-label">First name</label>
                                <input type="text" name="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Middle name</label>
                                <input type="text" name="middle_name"
                                    class="form-control"
                                    value="{{ old('middle_name') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Last name</label>
                                <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                                    class="form-select vehicle-category"
                                                    required>
                                                @foreach ($vehicle_categories as $vc)
                                                    <option value="{{ $vc->id }}"
                                                            data-type="{{ strtolower($vc->name) }}"
                                                            {{ old('vehicle_categories.' . $index) == $vc->id ? 'selected' : '' }}>
                                                        {{ $vc->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Vehicle Type --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Vehicle Type</label>
                                            <select name="vehicle_types[]" class="form-select" required>
                                                @foreach ($vehicle_types as $vt)
                                                    <option value="{{ $vt->id }}"
                                                            {{ $oldVehicleType == $vt->id ? 'selected' : '' }}>
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

                                        <div class="col-md-4 permit-field">
                                            <label class="form-label">Permit No</label>
                                            <input type="text" name="permit_no[]"
                                                value="{{ old('permit_no.' . $index) }}"
                                                class="form-control @error('permit_no.' . $index) is-invalid @enderror">

                                            @error('permit_no.' . $index)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- <div class="col-md-4 mt-3 permit-field">
                                            <label class="form-label">Permit No</label>
                                            <input type="text" name="permit_no[]"
                                                value="{{ old('permit_no.' . $index) }}"
                                                class="form-control">
                                        </div> --}}

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

                                        {{-- Remove --}}
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
    </div>
@endsection


@push('script_2')
<script>
    function updateRemoveButtons() {
        let rows = document.querySelectorAll('.vehicle-row');
        let buttons = document.querySelectorAll('.remove-vehicle-btn');

        if (rows.length === 1) {
            buttons.forEach(btn => btn.style.display = 'none');
        } else {
            buttons.forEach(btn => btn.style.display = 'inline-block');
        }
    }

    function togglePermitField(row) {
        let select = row.querySelector('.vehicle-category');
        let selectedOption = select.options[select.selectedIndex];
        let permitField = row.querySelector('.permit-field');

        if (selectedOption.dataset.type === 'private') {
            permitField.style.display = 'none';
            permitField.querySelector('input').value = '';
        } else {
            permitField.style.display = 'block';
        }
    }

    function attachCategoryChangeEvent(row) {
        let select = row.querySelector('.vehicle-category');

        select.addEventListener('change', function () {
            togglePermitField(row);
        });

        togglePermitField(row); // run on load
    }

    // ADD VEHICLE
    document.getElementById('add-vehicle-btn').addEventListener('click', function () {

        let container = document.getElementById('vehicle-container');
        let newRow = container.querySelector('.vehicle-row').cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        newRow.querySelector('.remove-vehicle-btn').addEventListener('click', function () {
            newRow.remove();
            updateRemoveButtons();
        });

        attachCategoryChangeEvent(newRow);

        container.appendChild(newRow);
        updateRemoveButtons();
    });

    // REMOVE BUTTON
    document.querySelectorAll('.remove-vehicle-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            btn.closest('.vehicle-row').remove();
            updateRemoveButtons();
        });
    });

    // INIT
    document.querySelectorAll('.vehicle-row').forEach(row => {
        attachCategoryChangeEvent(row);
    });

    updateRemoveButtons();

</script>
@endpush