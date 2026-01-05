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
                        <li class="breadcrumb-item active">List</li>
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
                <input type="hidden" name="type" value="bluebook">
                
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Bluebook Detail - {{ $renewal->vehicle->registration_no ?? 'N/A' }}</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                {{-- <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="book_number" class="form-label">Book Number</label>
                                        <input type="text" class="form-control @error('book_number') is-invalid @enderror" id="book_number" 
                                        name="book_number" value="{{ old('book_number', $renewal->book_number) }}" 
                                        placeholder="Ex: Two Wheeler" disabled>
                                        
                                        @error('book_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="issue_date" class="form-label">Issue Date</label>
                                        {{-- <input type="text" class="form-control @error('issue_date') is-invalid @enderror" id="issue_date" name="issue_date" value="{{ old('issue_date', $renewal->issue_date) }}" placeholder="Ex: Issue Date"> --}}
                                        <input type="text" class="form-control nepali-date @error('issue_date') is-invalid @enderror" name="issue_date"
                                            value="{{ old('issue_date', $renewal->issue_date) }}" placeholder="Select Issue Date" autocomplete="off"/>

                                        @error('issue_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="last_expiry_date" class="form-label">Last Expiry Date</label>
                                        {{-- <input type="text" class="form-control @error('last_expiry_date') is-invalid @enderror" id="last_expiry_date" name="last_expiry_date" value="{{ old('last_expiry_date', $renewal->last_expiry_date) }}" placeholder="Ex: Last Expiry Date"> --}}
                                        
                                        <input type="text" class="form-control nepali-date @error('last_expiry_date') is-invalid @enderror" name="last_expiry_date"
                                            value="{{ old('last_expiry_date', $renewal->last_expiry_date) }}" placeholder="Select Expiry Date" autocomplete="off"/>
                                        
                                        @error('last_expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" 
                                            name="expiry_date" value="{{ old('expiry_date', $renewal->expiry_date) }}" 
                                            placeholder="Ex: Expiry Date" disabled>
                                        
                                        @error('expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select class="form-select" name="status">
                                            <option value="paid" {{ old('status', $renewal->status) === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="unpaid" {{ old('status', $renewal->status) === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
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
                            <button type="button" class="btn btn-soft-danger waves-effect">
                                <i class="ri-printer-line align-bottom me-1"></i> Reset
                            </button>
                            
                            <button type="submit" class="btn btn-soft-success btn-success">
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

