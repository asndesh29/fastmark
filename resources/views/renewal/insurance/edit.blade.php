@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Renewal</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Insurance</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Insurance Renewal List -->
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.renewal.insurance.update', $renewal->id) }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $renewal->vehicle_id }}">
                <input type="hidden" name="renewable_type" value="insurance">
                
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Insurance Detail - {{ $renewal->vehicle->registration_no ?? 'N/A' }}</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                               <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="provider_id" class="form-label">Insurance Provider</label>
                                        <select name="provider_id" id="provider_id" class="form-select">
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->id }}" 
                                                    @selected(old('provider_id', $selectedProviderId ?? '') == $provider->id)>
                                                    {{ $provider->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                               </div>

                               <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Insurance Type</label>
                                        <select class="form-select" name="insurance_type">
                                            <option value="general" {{ $renewal->insurance_type === 'general' ? 'selected' : '' }}>General</option>
                                            <option value="third" {{ $renewal->insurance_type === 'third' ? 'selected' : '' }}>Third</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="policy_number" class="form-label">Policy Number</label>
                                        <input 
                                            type="text" 
                                            class="form-control @error('policy_number') is-invalid @enderror" 
                                            id="policy_number" 
                                            name="policy_number" 
                                            value="{{ old('policy_number', $renewal->policy_number) }}" 
                                            placeholder="Ex: 3000">
                                        
                                        @error('policy_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="policy_number" class="form-label">Policy Number</label>
                                        <input type="text" class="form-control @error('policy_number') is-invalid @enderror" id="policy_number" name="policy_number" value="{{ old('policy_number', $renewal->policy_number) }}" placeholder="Enter policy number">
                                        
                                        @error('policy_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="expiry_date_bs" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control nepali-date @error('expiry_date_bs') is-invalid @enderror" 
                                            name="expiry_date_bs"
                                            value="{{ old('expiry_date_bs', $renewal->expiry_date_bs) }}" 
                                            placeholder="Select Expiry Date" 
                                           readonly />

                                        @error('expiry_date_bs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input 
                                            type="integer" 
                                            class="form-control @error('renewal_charge') is-invalid @enderror" 
                                            id="renewal_charge" 
                                            name="renewal_charge" 
                                            value="{{ old('renewal_charge', $renewal->renewal_charge) }}" 
                                            placeholder="Ex: 3000">
                                        
                                        @error('renewal_charge')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select class="form-select" name="payment_status">
                                            <option value="paid" {{ $renewal->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="unpaid" {{ $renewal->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
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
                            <a href="{{ route('admin.renewal.insurance.index') }}">
                                <button type="button" class="btn btn-soft-danger waves-effect">
                                    <i class="ri-close-line align-bottom me-1"></i> Reset
                                </button>
                            </a>
                            
                            <button type="submit" class="btn btn-soft-success btn-success">
                                <i class="ri-save-line align-bottom me-1"></i> Update
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



