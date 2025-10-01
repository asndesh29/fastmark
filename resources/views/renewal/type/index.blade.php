@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal Type</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Renewal Type</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <form action="{{ route('admin.renewal.type.store') }}" method="POST">
                    @csrf
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Private">
                                        
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- button -->
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Save</button>
                            </div>
                            <!-- button -->
                        </div>
                    </form>
                </div>
            </div>
            <!-- customer -->
        </div> 
        <!-- end col -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Renewal Type List</h4>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($renewal_types as $Key => $rt)
                                    <tr>
                                        <td>{{ $loop->iteration}}</td>
                                        <td>{{ $rt->name }}</td>
                                        <td>
                                            <div class="form-check form-switch form-switch-right form-switch-md">
                                                <input 
                                                    type="checkbox" class="form-check-input code-switcher toggle-switch-input status_change_alert" 
                                                    data-url="{{ route('admin.renewal.type.status', [$rt->id, $rt->is_active ? 0 : 1]) }}"
                                                    data-message="{{$rt->is_active ? 'you want to deactivate this renewal type' : 'you want to activate this renewal type' }}"
                                                    id="status_change_alert_{{ $rt->id }}" 
                                                    {{ $rt->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a href="{{ route('admin.renewal.type.edit', $rt->id) }}">
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
                                                        data-id="{{ $rt->id }}"
                                                        data-name="{{ $rt->name ?? '' }}"> <!-- Optional: display name in alert -->
                                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                                    </button>

                                                    <!-- Delete form -->
                                                    <form action="{{ route('admin.renewal.type.destroy', [$rt->id]) }}"
                                                        method="post" id="renew-{{ $rt->id }}" style="display: none;">
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
            <!-- customer -->
        </div> 
        <!-- end col -->
    </div>
@endsection

@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>
@endpush

