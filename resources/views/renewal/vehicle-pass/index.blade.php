@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Vehicle Tax</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Add Vehicle Tax -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Add New Vehicle Tax</h5>
                </div>

                <div class="card-body">
                    <div class="live-preview">
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">Vehicle Number</label>
                                        <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" placeholder="Ex: Ba 83 Pa 8297">
                                        
                                        @error('vehicle_number')
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Vehicle Tax -->

    <!-- Vehicle Tax List -->
    <div class="row">
        <!-- start col -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Vehicle Tax List</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div>
                <!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <div class="listjs-table" id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        <tr>
                                            <td></td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a href="">
                                                            <button type="button" class="btn btn-outline-primary btn-sm btn-icon waves-effect waves-light">
                                                                <i class="ri-edit-fill"></i>
                                                            </button>
                                                        </a>
                                                    </li>

                                                    <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                        <a href="">
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
                                    </tbody>
                                </table>
                                
                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any orders for you search.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                    <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                    Previous
                                    </a>
                                    <ul class="pagination listjs-pagination mb-0">
                                        <li class="active"><a class="page" href="#" data-i="1" data-page="8">1</a></li>
                                        <li><a class="page" href="#" data-i="2" data-page="8">2</a></li>
                                    </ul>
                                    <a class="page-item pagination-next" href="javascript:void(0);">
                                    Next
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!-- end col -->
    </div>
    <!-- Vehicle Tax List -->
@endsection

