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
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Renewal List</h4>

                </div><!-- end card header -->

                <div class="card-body">
                    <p class="text-muted mb-4">Use <code>table-card</code> class to show card-based table within a &lt;tbody&gt;.</p>

                    <div class="live-preview">
                        <div class="table-responsive table-card">
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Customer Name</th>
                                        <th>Vehicle Type</th>
                                        <th>Registration No</th>
                                        <th>Chassis No</th>
                                        <th>Engine No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @if (count($renewal_lists) > 0)
                                        @foreach ($renewal_lists as $key => $renewal)
                                            <tr>
                                                <td class="fw-medium">{{ $key+$renewal_lists->firstItem() }}</td>
                                                <td>{{ $renewal->owner->first_name }} {{ $renewal->owner->last_name }}</td>
                                                <td>{{ $renewal->vehicleType->name }}</td>
                                                <td>{{ $renewal->registration_no }}</td>
                                                <td>{{ $renewal->chassis_no }}</td>
                                                <td>{{ $renewal->engine_no }}</td>

                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                            <a href="{{ route('admin.renewal.show', $renewal->id) }}">
                                                                <button type="button" class="btn btn-outline-warning btn-sm btn-icon waves-effect waves-light">
                                                                    <i class="ri-eye-fill"></i>
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
                    
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- customer -->
@endsection

