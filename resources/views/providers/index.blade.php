@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Insurance Provider</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Insurance Provider</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.settings.insurance-provider.store') }}" method="POST">
                @csrf
                <!-- customer -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add New Insurance Provider</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">First name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Shikhar Insurance">
                                        
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault02" class="form-label">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Ex: Kamalbinayak, Bhaktapur">
                                        
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone_no') is-invalid @enderror" id="phone_no" name="phone_no" value="{{ old('phone_no') }}" placeholder="Ex: +977-">
                                        
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

                            <!-- button -->
                            <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Save</button>
                            </div>
                            <!-- button -->
                        </div>
                    </div>
                </div>
                <!-- customer -->
            </form>
        </div> 
        <!-- end col -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Insurance Provider List</h4>
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
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($providers as $key => $provider)
                                        <tr>
                                            <td>{{ $key + $providers->firstItem() }}</td>
                                            <td>{{ $provider->name }}</td>
                                            <td>{{ $provider->address }}</td>
                                            <td>{{ $provider->email }}</td>
                                            <td>{{ $provider->phone_no }}</td>
                                            <td>
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <input 
                                                        type="checkbox" class="form-check-input code-switcher toggle-switch-input status_change_alert" 
                                                        data-url="{{ route('admin.settings.insurance-provider.status', [$provider->id, $provider->is_active ? 0 : 1]) }}"
                                                        data-message="{{$provider->is_active ? 'you want to deactivate this insurance provider' : 'you want to activate this insurance provider' }}"
                                                        id="status_change_alert_{{ $provider->id }}" 
                                                        {{ $provider->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a href="{{ route('admin.settings.insurance-provider.edit', $provider->id) }}">
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
                                                            data-id="{{ $provider->id }}"
                                                            data-name="{{ $provider->name ?? '' }}"> 
                                                            <i class="ri-delete-bin-5-fill fs-16"></i>
                                                        </button>

                                                        <!-- Delete form -->
                                                        <form action="{{ route('admin.settings.insurance-provider.destroy', [$provider->id]) }}"
                                                            method="post" id="renew-{{ $provider->id }}" style="display: none;">
                                                            @csrf @method('delete')
                                                        </form>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                @if ($providers->hasPages())
                                    <tr>
                                        <td colspan="7">
                                            <div class="d-flex justify-content-end">
                                                {!! $providers->links('pagination::bootstrap-5') !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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

