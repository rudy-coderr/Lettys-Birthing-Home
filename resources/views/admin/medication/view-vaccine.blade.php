@extends('layouts.admin.layout')

@section('title', 'Vaccine Details - Letty\'s Birthing Home')
@section('page-title', 'Inventory Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="form-card">
            <div class="form-header">
                <h5 id="formTitle">
                    <i class="fas fa-eye me-2"></i> Vaccine Details
                </h5>
                <div class="header-actions">
                    <a href="{{ route('admin.inventory.vaccines') }}" class="back-button">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>

                    <button type="button" class="edit-button" id="editToggleBtn" onclick="toggleEditMode()">
                        <i class="fas fa-edit me-2"></i> <span id="editBtnText">Edit</span>
                    </button>


                </div>
            </div>

            <div class="alert alert-warning" id="editModeAlert" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span>You are now in edit mode. Make your changes and click "Update Vaccine" to save.</span>
            </div>

            <form id="editVaccineForm" action="{{ route('vaccines.update', $vaccine->id) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-info-circle me-2"></i> Basic Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="item_name">Vaccine Name <span class="required">*</span></label>
                            <input type="text" id="item_name" name="item_name" class="form-control editable-field"
                                value="{{ $vaccine->item_name }}" readonly required>
                            <div class="invalid-feedback">Vaccine Name is required</div>
                        </div>
                        <div class="form-group">
                            <label for="batch_no">Batch Number</label>
                            <input type="text" id="batch_no" name="batch_no" class="form-control editable-field"
                                value="{{ $vaccine->batch_no }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Inventory Details -->
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-warehouse me-2"></i> Inventory Details</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">Quantity <span class="required">*</span></label>
                            <input type="number" id="quantity" name="quantity" class="form-control editable-field"
                                value="{{ $vaccine->quantity }}" min="0" readonly required>
                            <div class="invalid-feedback">Quantity is required</div>
                        </div>
                        <div class="form-group">
                            <label for="unit_id">Unit <span class="required">*</span></label>
                            <select id="unit_id" name="unit_id" class="form-select editable-field" disabled required>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ $vaccine->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Unit is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" class="form-control editable-field"
                                value="{{ $vaccine->expiry_date }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="reorder_level">Minimum Stock</label>
                            <input type="number" id="reorder_level" name="reorder_level"
                                class="form-control editable-field" value="{{ $vaccine->reorder_level }}" min="0"
                                readonly>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-section form-actions" id="formActions" style="display: none;">
                    <button type="submit" class="btnn btn-primary">
                        <i class="fas fa-save me-2"></i> Update Vaccine
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('script/admin/edit-vaccine.js') }}"></script>
@endsection
