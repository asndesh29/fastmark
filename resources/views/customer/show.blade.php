@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Customer Detail</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->


    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-lg-3">
            <!-- customer details -->
            <div class="card">
                <div class="card-body p-4">
                    <div>
                        <div class="flex-shrink-0 avatar-md mx-auto">
                            <div class="avatar-title bg-light rounded">
                                <img src="{{ $customer->image_full_url ?? dynamicAsset('assets/images/companies/img-2.png') }}" alt="" height="50">
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <h5 class="mb-1">{{ $customer->first_name }} {{ $customer->last_name }}</h5>
                            <p class="text-muted">#00{{ $customer->id }}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 table-borderless">
                                <tbody>
                                    <tr>
                                        <th><span class="fw-medium">Email</span></th>
                                        <td>{{ $customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th><span class="fw-medium">Contact No.</span></th>
                                        <td>+(977) {{ $customer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th><span class="fw-medium">Location</span></th>
                                        <td>{{ $customer->address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!-- customer details -->
        </div> 

        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Vehicle Info</h4>
                </div>

                <div class="card-body">
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
                                        <th scope="col" style="width: 50px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                            </div>
                                        </th>
                                        <th class="sort" data-sort="vehicle_type">Type</th>
                                        <th class="sort" data-sort="registration_no">Registration No</th>
                                        <th class="sort" data-sort="chassis_no">Chassis No</th>
                                        <th class="sort" data-sort="engine_cc">Engine CC</th>
                                        <th class="sort" data-sort="last_renewed_at">Last Renewed At</th>
                                        <th class="sort" data-sort="expiry_date">Expirty Date</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                    @foreach ($customer->vehicles as $key => $cv )
                                        <tr>
                                            <th scope="row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                                </div>
                                            </th>
                                            <td class="id" style="display:none;">
                                                <a href="javascript:void(0);" class="fw-medium link-primary">#VZ10</a>
                                            </td>
                                            <td class="vehicle_type">{{ $cv->vehicleType->name }}</td>
                                            <td class="registration_no">{{ $cv->registration_no }}</td>
                                            <td class="chassis_no">{{ $cv->chassis_no }}</td>
                                            <td class="engine_cc">{{ $cv->engine_cc }}</td>
                                            <td class="last_renewed_at">{{ $cv->last_renewed_at }}</td>
                                            <td class="expiry_date">{{ $cv->expiry_date }}</td>
                                            {{-- <td>
                                                <div class="d-flex gap-2">
                                                    <div class="edit">
                                                        <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" data-bs-target="#showModal">Edit</button>
                                                    </div>
                                                    <div class="remove">
                                                        <button class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Remove</button>
                                                    </div>
                                                </div>
                                            </td> --}}
                                        </tr>
                                    @endforeach
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
        <!-- end col -->
    </div>
    <!-- end row -->
</script>
@endsection

