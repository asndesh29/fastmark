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

    <!-- Pollution Renewal List -->
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.vehicle-tax.update', $renewal->id) }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $renewal->vehicle_id }}">
                <input type="hidden" name="type" value="vehicle-tax">
                
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Vehicle Tax Detail</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="invoice_number" class="form-label">Invoice Number</label>
                                        <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $renewal->invoice_no) }}" placeholder="Ex: Inovie No">
                                        
                                        @error('invoice_number')
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
                                        <label for="last_expiry_date" class="form-label">Last Expiry Date</label>
                                        <input type="text" class="form-control @error('last_expiry_date') is-invalid @enderror" id="last_expiry_date" name="last_expiry_date" value="{{ old('last_expiry_date', $renewal->last_expiry_date) }}" placeholder="Ex: Last Expiry Date">
                                        
                                        @error('last_expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $renewal->expiry_date) }}" placeholder="Ex: Expiry Date">
                                        
                                        @error('expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tax_amount" class="form-label">Tax Amount</label>
                                        <input type="text" class="form-control @error('tax_amount') is-invalid @enderror" id="tax_amount" name="tax_amount" value="{{ old('tax_amount', $renewal->tax_amount) }}" placeholder="Ex: 3000">
                                        
                                        @error('tax_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="renewal_charge" class="form-label">Renewal Charge</label>
                                        <input type="text" class="form-control @error('renewal_charge') is-invalid @enderror" id="renewal_charge" name="renewal_charge" value="{{ old('renewal_charge', $renewal->renewal_charge) }}" placeholder="Ex: 3000">
                                        
                                        @error('renewal_charge')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="income_tax" class="form-label">Income Tax</label>
                                        <input type="text" class="form-control @error('income_tax') is-invalid @enderror" id="income_tax" name="income_tax" value="{{ old('income_tax', $renewal->income_tax) }}" placeholder="Ex: 3000">
                                        
                                        @error('income_tax')
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
    <!-- Pollution Renewal Detail -->
@endsection


