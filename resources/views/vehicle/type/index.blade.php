@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Vehicle Type</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
             <form action="{{ route('admin.vehicle.type.store') }}" method="POST">
                @csrf
                <!-- Vehicle Type -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Vehicle Type</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Two Wheeler">
                                        
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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


    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Vehicle Type List</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="table-responsive table-card">
                            <table class="table table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @foreach ($vehicleTypes as $key => $vt )
                                        <tr>
                                            <td>{{ $key + $vehicleTypes->firstItem() }}</td>
                                            <td>{{ $vt->name }}</td>
                                            <td>
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <input 
                                                        type="checkbox" class="form-check-input code-switcher toggle-switch-input status_change_alert" 
                                                        data-url="{{ route('admin.vehicle.type.status', [$vt->id, $vt->is_active ? 0 : 1]) }}"
                                                        data-message="{{$vt->is_active ? 'you want to deactivate this vehicle type' : 'you want to activate this vehicle type' }}"
                                                        id="status_change_alert_{{ $vt->id }}" 
                                                        {{ $vt->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a href="{{ route('admin.vehicle.type.edit', $vt->id) }}">
                                                            <button type="button" class="btn btn-outline-primary btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-edit-fill"></i>
                                                            </button>
                                                        </a>
                                                    </li>

                                                    <!-- Delete button -->
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Remove">

                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-sm btn-icon waves-effect waves-light delete-btn"
                                                            data-id="{{ $vt->id }}"
                                                            data-name="{{ $vt->name ?? '' }}"> 
                                                            <i class="ri-delete-bin-5-fill fs-16"></i>
                                                        </button>

                                                        <!-- Delete form -->
                                                        <form action="{{ route('admin.vehicle.type.destroy', [$vt->id]) }}"
                                                            method="post" id="renew-{{ $vt->id }}" style="display: none;">
                                                            @csrf @method('delete')
                                                        </form>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- customer -->
        </div> 
        <!-- end col -->
    </div>
    <!-- customer -->
@endsection

@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>
@endpush
