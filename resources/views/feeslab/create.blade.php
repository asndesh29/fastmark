@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Fee Slab</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Fee Slab</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.feeslab.store') }}" method="POST">
                @csrf
                <!-- customer -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Fee Slab</h4>
                        <div class="flex-shrink-0">
                            <div class="form-check form-switch form-switch-right form-switch-md">
                                <label for="FormValidationDefault" class="form-label text-muted">Show Code</label>
                                <input class="form-check-input code-switcher" type="checkbox" id="FormValidationDefault">
                            </div>
                        </div>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                        <select class="form-select mb-3" name="vehicle_type_id" id="vehicle_type_id">
                                            @foreach ($vehicle_types as $key => $vt )
                                                <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault02" class="form-label">Minimunm CC</label>
                                        <input type="text" class="form-control @error('min_cc') is-invalid @enderror" id="min_cc" name="min_cc" value="{{ old('min_cc') }}" placeholder="Ex: Sandesh">
                                        
                                        @error('min_cc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="max_cc" class="form-label">Maximum CC</label>
                                        <input type="text" class="form-control @error('max_cc') is-invalid @enderror" id="max_cc" name="max_cc" value="{{ old('max_cc') }}" placeholder="Ex: +977-">
                                        
                                        @error('max_cc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="base_fee" class="form-label">Base Fee</label>
                                        <input type="text" class="form-control @error('base_fee') is-invalid @enderror" id="base_fee" name="base_fee" value="{{ old('base_fee') }}" placeholder="Ex: john@ex.com">
                                        
                                        @error('base_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="late_fee" class="form-label">Late Fee</label>
                                        <input type="late_fee" class="form-control @error('late_fee') is-invalid @enderror" id="late_fee" name="late_fee" value="{{ old('late_fee') }}" placeholder="Ex: john@ex.com">
                                        
                                        @error('late_fee')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- customer -->

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
@endsection

