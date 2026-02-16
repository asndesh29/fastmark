@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Forms Validation</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Forms Validation</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="{{ route('admin.vehicle.update', $vehicle->id) }}">
    @csrf
    @method('PUT')
    <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0 flex-grow-1">Update Vehicle Info</h4>
        </div>

        <div class="card-body">
            <div class="row">

                <!-- Vehicle Category -->
                <div class="col-md-4">
                    <label for="vehicle_category_id" class="form-label">Vehicle Category</label>
                    <select class="form-select" name="vehicle_category_id" required>
                        <option value="">Select Category</option>
                        @foreach($vehicle_categories as $vc)
                            <option value="{{ $vc->id }}" {{ $vehicle->vehicle_category_id == $vc->id ? 'selected' : '' }}>
                                {{ $vc->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Vehicle Type -->
                <div class="col-md-4">
                    <label for="vehicle_type_id" class="form-label">Vehicle Type</label>
                    <select class="form-select" name="vehicle_type_id" required>
                        <option value="">Select Type</option>
                        @foreach($vehicle_types as $vt)
                            <option value="{{ $vt->id }}" {{ $vehicle->vehicle_type_id == $vt->id ? 'selected' : '' }}>
                                {{ $vt->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_type_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Registration No -->
                <div class="col-md-4">
                    <label for="registration_no" class="form-label">Registration No</label>
                    <input type="text" name="registration_no" class="form-control" value="{{ old('registration_no', $vehicle->registration_no) }}">
                    @error('registration_no')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Permit No -->
                <div class="col-md-4 mt-3">
                    <label for="permit_no" class="form-label">Permit No</label>
                    <input type="text" name="permit_no" class="form-control" value="{{ old('permit_no', $vehicle->permit_no) }}">
                    @error('permit_no')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Chassis No -->
                <div class="col-md-4 mt-3">
                    <label for="chassis_no" class="form-label">Chassis No</label>
                    <input type="text" name="chassis_no" class="form-control" value="{{ old('chassis_no', $vehicle->chassis_no) }}">
                </div>

                <!-- Engine No -->
                <div class="col-md-4 mt-3">
                    <label for="engine_no" class="form-label">Engine No</label>
                    <input type="text" name="engine_no" class="form-control" value="{{ old('engine_no', $vehicle->engine_no) }}">
                </div>

                <!-- Engine CC -->
                <div class="col-md-4 mt-3">
                    <label for="engine_cc" class="form-label">Engine CC</label>
                    <input type="text" name="engine_cc" class="form-control" value="{{ old('engine_cc', $vehicle->engine_cc) }}">
                </div>

                <!-- Capacity -->
                <div class="col-md-4 mt-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="text" name="capacity" class="form-control" value="{{ old('capacity', $vehicle->capacity) }}">
                </div>

            </div>
        </div>

        <div class="card-footer text-end">
            <button type="submit" class="btn btn-success">
                <i class="ri-save-line align-bottom me-1"></i> Save
            </button>
        </div>
    </div>
</form>

        </div> 
        <!-- end col -->
    </div>
    <!-- customer -->
@endsection

