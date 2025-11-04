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
                <input type="hidden" name="type" value="insurance">
                
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Insurance Detail</h4>
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
                                        <label for="policy_number" class="form-label">Policy Number</label>
                                        <input type="text" class="form-control @error('policy_number') is-invalid @enderror" id="policy_number" name="policy_number" value="{{ old('policy_number', $renewal->policy_number) }}" placeholder="Enter policy number">
                                        
                                        @error('policy_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="issue_date" class="form-label">Issue Date</label>
                                        <input type="text" class="form-control @error('issue_date') is-invalid @enderror" id="issue_date" name="issue_date" value="{{ old('issue_date', $renewal->issue_date) }}" placeholder="Ex: Issue Date">
                                        
                                        @error('issue_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="text" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $renewal->amount) }}" placeholder="Ex: 3000">
                                        
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select class="form-select" name="status">
                                            <option value="paid" {{ $renewal->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="unpaid" {{ $renewal->status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
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
                            <button type="button" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Reset</button>
                            <button type="submit" class="btn btn-success"><i class="ri-printer-line align-bottom me-1"></i> Save</button>
                        </div>
                        <!-- button -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Bluebook Renewal List -->

    
@endsection


