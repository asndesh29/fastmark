@extends('layouts.app')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Update Renewal Type</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.settings.renewal-type.update', $renewal_type) }}" method="POST">
                @csrf
                <!-- Vehicle Category -->
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Update Renewal Type</h4>
                    </div>
                    <!-- end card header -->

                    <div class="card-body">
                        <div class="live-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="validationDefault01" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $renewal_type) }}"
                                            placeholder="Ex: Two Wheeler">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Private Validity --}}
                                @php
                                    $selectedPrivateUnit = old('private_validity_value',$renewal_type->private_validity_unit ?? '');
                                @endphp

                                <div class="col-md-3">
                                    <label class="form-label">Private Validity</label>
                                    <div class="d-flex gap-2">
                                        <input type="number" name="private_validity_value" class="form-control"
                                            placeholder="Value"
                                            value="{{ old('private_validity_value', $renewal_type->private_validity_value) }}">

                                        <select name="private_validity_unit" class="form-select">
                                            <option value="">Unit</option>
                                            <option value="days" {{ $selectedPrivateUnit == 'days' ? 'selected' : '' }}>Days</option>
                                            <option value="months" {{ $selectedPrivateUnit == 'months' ? 'selected' : '' }}>Months</option>
                                            <option value="years" {{ $selectedPrivateUnit == 'years' ? 'selected' : '' }}>Years</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Commercial Validity --}}
                                {{-- @php
                                    $selectedCommercialUnit = old('commercial_validity_unit',$renewal_type->commercial_validity_unit ?? '');
                                @endphp --}}
                                @php
                                    $units = ['days', 'months', 'years'];
                                    $selectedCommercialUnit = old(
                                        'commercial_validity_unit',
                                        $renewal_type->commercial_validity_unit ?? ''
                                    );
                                @endphp
                                <div class="col-md-3">
                                    <label class="form-label">Commercial Validity</label>
                                    <div class="d-flex gap-2">
                                        <input type="number" name="commercial_validity_value" class="form-control"
                                            placeholder="Value" value="{{ old('commercial_validity_value', $renewal_type->commercial_validity_value) }}">

                                        <select name="commercial_validity_unit" class="form-select">
                                            <option value="">Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit }}"
                                                    {{ $selectedCommercialUnit == $unit ? 'selected' : '' }}>
                                                    {{ ucfirst($unit) }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- button -->
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <button type="button" class="btn btn-danger">
                                <i class="ri-printer-line align-bottom me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line align-bottom me-1"></i>Save
                            </button>
                        </div>
                        <!-- button -->
                    </div>
                </div>
                <!-- Vehicle Category -->
            </form>
        </div>
    </div>
@endsection