@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Update Vehicle Type</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
             <form action="{{ route('admin.settings.vehicle.type.update', $vehicleType) }}" method="POST">
                @csrf
                <!-- Vehicle Type -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Vehicle Type</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $vehicleType->name ) }}" placeholder="Ex: Two Wheeler">
                                        
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault02" class="form-label">Service Charge</label>
                                        <input type="text" class="form-control @error('service_charge') is-invalid @enderror" id="service_charge" name="service_charge" value="{{ old('service_charge', $vehicleType->service_charge ) }}" placeholder="Ex: 300">
                                        
                                        @error('service_charge')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <!-- button -->
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <button type="button" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Reset</button>
                            <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Save</button>
                        </div>
                        <!-- button -->
                    </div>
                </div>
                <!-- Vehicle Type -->
            </form>
        </div>
    </div>
@endsection

