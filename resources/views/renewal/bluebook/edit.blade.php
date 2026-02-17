@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Bluebook</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Bluebook Renewal List -->
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.renewal.bluebook.update', $renewal->id) }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $renewal->vehicle_id }}">
                <input type="hidden" name="renewable_type" value="bluebook">
                
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Bluebook Detail - {{ $renewal->vehicle->registration_no ?? 'N/A' }}</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="last_expiry_date" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control nepali-date @error('expiry_date_bs') is-invalid @enderror" name="expiry_date_bs"
                                            value="{{ old('expiry_date_bs', $renewal->expiry_date_bs) }}" placeholder="Select Expiry Date" autocomplete="off"/>
                                        
                                        @error('expiry_date_bs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Payment Status</label>
                                        <select class="form-select" name="payment_status">
                                            <option value="paid" {{ old('payment_status', $renewal->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="unpaid" {{ old('payment_status', $renewal->payment_status) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        </select>

                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks">{{ $renewal->remarks }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- button -->
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <a href="{{ route('admin.renewal.bluebook.index') }}">
                                <button type="button" class="btn btn-danger waves-effect">
                                    <i class="ri-printer-line align-bottom me-1"></i> Cancel
                                </button>
                            </a>
                            
                            <button type="submit" class="btn btn-success btn-success">
                                <i class="ri-printer-line align-bottom me-1"></i> Update
                            </button>
                        </div>
                        <!-- button -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Bluebook Renewal List -->
@endsection

@push('script_2')
    <script src="{{ dynamicAsset('assets/js/custom.js') }}"></script>
@endpush

