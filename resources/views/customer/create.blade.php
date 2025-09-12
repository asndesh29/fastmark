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
            <form action="{{ route('admin.renewal.store') }}" method="POST">
                @csrf
                <!-- customer -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Customer Info</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">First name</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Ex: Sandesh">
                                        
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault02" class="form-label">Last name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Ex: Sandesh">
                                        
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Ex: +977-">
                                        
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ex: john@ex.com">
                                        
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- customer -->

                <!-- vehicle -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Vehicle Info</h4>
                        <div class="flex-shrink-0">
                            <!-- Button -->
                            <button type="button" class="btn btn-primary btn-md" id="add-vehicle-btn">Add New Vehicle</button>
                        </div>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div id="vehicle-container" style="padding: 16px;">
                                <div class="row vehicle-row mb-4 border rounded p-3" style="background-color: #f8f9fc">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                            <select class="form-select mb-3" name="vehicle_type[]" required>
                                                <option value="two_wheeler">Two Wheeler</option>
                                                <option value="four_wheeler">Four Wheeler</option>
                                                <option value="heavy">Heavy</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="registration_no" class="form-label">Registration No</label>
                                            <input type="text" name="registration_no[]" class="form-control" placeholder="Ex: Ba 83 Pa 8297">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="chassis_no" class="form-label">Chassis No</label>
                                            <input type="text" name="chassis_no[]" class="form-control" placeholder="Ex:">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="engine_no" class="form-label">Engine Number</label>
                                            <input type="text" name="engine_no[]" class="form-control" placeholder="Ex: +977-">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="engine_cc" class="form-label">Engine CC</label>
                                            <input type="text" name="engine_cc[]" class="form-control" placeholder="Ex: 1500">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="renewed_date" class="form-label">Last Renewed Date</label>
                                            <input type="date" name="renewed_date[]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- vehicle -->

                <!-- button -->
                <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                    <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Save</button>
                </div>
                <!-- button -->
            </form>
        </div> 
        <!-- end col -->
    </div>
    <!-- customer -->

    
<script>
    document.getElementById('add-vehicle-btn').addEventListener('click', function() {
        let container = document.getElementById('vehicle-container');
        let newRow = container.querySelector('.vehicle-row').cloneNode(true);

        // clear input values in the new row
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        container.appendChild(newRow);
    });
</script>
@endsection

