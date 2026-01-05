@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Customer</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Customer</a></li>
                        {{-- <li class="breadcrumb-item active">New</li> --}}
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('admin.customer.update', $customer) }}" method="post" enctype="multipart/form-data">
        @csrf
        <!-- Basic Information -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">First name</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name',  $customer->first_name) }}" placeholder="Ex: Sandesh">
                                        
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault02" class="form-label">Last name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $customer->last_name) }}" placeholder="Ex: Awal">
                                        
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="Ex: +977-">
                                        
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="Ex: john@ex.com">
                                        
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3" value="{{ $customer->address }}">
                                            {{ $customer->address }}
                                        </textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="live-preview"> 
                            <label for="product-image-viewer" class="form-label">Customer Identification Image</label>
                            <div class="text-center">
                                <div class="position-relative d-inline-block mb-4">
                                    <div class="avatar-lg">
                                        <div class="avatar-title bg-light rounded">
                                            <img src="{{ dynamicAsset('assets/images/product-img.png') }}"
                                                id="product-img" class="avatar-md h-auto" alt="Customer Image">
                                        </div>
                                    </div>
                                    <div class="position-absolute top-100 start-100 translate-middle">
                                        <label for="product-image-viewer" class="mb-0" data-bs-toggle="tooltip"
                                            data-bs-placement="right" title="Select Image">
                                            <div class="avatar-xs cursor-pointer">
                                                <div class="avatar-title bg-light border rounded-circle text-muted">
                                                    <i class="ri-image-fill"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <input type="file" class="form-control d-none" name="image"
                                            id="product-image-viewer" accept="image/*">
                                    </div>
                                </div>
                                <p class="opacity-75 max-w220 mx-auto text-center">
                                    Image format: jpg, png, jpeg, gif<br>
                                    Max size: 2MB<br>
                                    Ratio: 1:1
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <!-- Basic Information -->

        <!-- button -->
        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
            <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Update</button>
        </div>
        <!-- button -->
    </form>
    <!-- end col -->

@endsection

@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>
@endpush

