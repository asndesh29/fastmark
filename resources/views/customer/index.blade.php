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
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->


    <div class="row">
        <div class="col-lg-12">
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Customer List</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.renewal.create') }}">
                            <button type="button" class="btn btn-primary btn-sm" id="add-renewal-btn">Add New Renewal</button>
                        </a>
                    </div>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th>S.No.</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($customers) > 0)
                                    @foreach ($customers as $key => $customer)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option">
                                                </div>
                                            </td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                            <td>{{ $customer->email }}</td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>
                                                <div class="status">
                                                    <div class="form-check form-switch form-switch-mdform-switch form-switch-md">
                                                        <input 
                                                            type="checkbox" 
                                                            class="form-check-input code-switcher toggle-switch-input status_change_alert"
                                                            data-url="{{ route('admin.customer.status', [$customer->id, $customer->is_active ? 0 : 1]) }}"
                                                            data-message="{{ $customer->is_active ? 'you want to deactivate this customer' : 'you want to activate this customer' }}"
                                                            id="status_{{ $customer->id }}"
                                                            {{ $customer->is_active ? 'checked' : '' }}
                                                        >
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a href="{{ route('admin.customer.edit', $customer->id) }}">
                                                            <button type="button" class="btn btn-outline-primary btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-edit-fill"></i>
                                                            </button>
                                                        </a>
                                                    </li>

                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                        <a href="{{ route('admin.customer.show', $customer->id) }}">
                                                            <button type="button" class="btn btn-outline-warning btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-eye-fill"></i>
                                                            </button>
                                                        </a>
                                                    </li>

                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                        <a href="">
                                                            <button type="button" class="btn btn-outline-danger btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-delete-bin-5-line"></i>
                                                            </button>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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
    <script src="{{ asset('assets/js/custom.js') }}"></script>
@endpush
