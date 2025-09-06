@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Renewal</a></li>
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
                                    <th data-ordering="false">SR No.</th>
                                    <th data-ordering="false">ID</th>
                                    <th data-ordering="false">Purchase ID</th>
                                    <th data-ordering="false">Title</th>
                                    <th data-ordering="false">User</th>
                                    <th>Assigned To</th>
                                    <th>Created By</th>
                                    <th>Create Date</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
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

