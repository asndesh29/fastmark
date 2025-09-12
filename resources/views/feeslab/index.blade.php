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
            <!-- customer -->
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"></h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.feeslab.create') }}">
                            <button type="button" class="btn btn-primary btn-sm" id="add-renewal-btn">Add New Fee Slab</button>
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
                                    <th>Vehicle Type</th>
                                    <th>Min CC</th>
                                    <th>Max CC</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fee_slabs as $key => $fee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($fee->vehicleType->name) }}</td>
                                        <td>{{ $fee->min_cc }}</td>
                                        <td>{{ $fee->max_cc }}</td>
                                        <td>{{ $fee->base_fee }}</td>
                                        <td>{{ $fee->is_active }}</td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a href="{{ route('admin.feeslab.edit', $fee->id) }}">
                                                        <button type="button" class="btn btn-outline-primary btn-sm btn-icon waves-effect waves-light">
                                                            <i class="ri-edit-fill"></i>
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

