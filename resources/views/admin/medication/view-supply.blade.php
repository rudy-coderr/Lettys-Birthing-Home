@extends('layouts.admin.layout')

@section('title', 'Supply Details - Letty\'s Birthing Home')
@section('page-title', 'Inventory Management')

@section('content')
<div class="container-fluid main-content">
    <div class="form-card">
        <div class="form-header">
            <h5 id="formTitle">
                <i class="fas fa-eye me-2"></i> Supply Details
            </h5>
            <div class="header-actions">
                <a href="{{ route('admin.inventory.supplies') }}" class="back-button">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
                <button type="button" class="edit-button" id="editToggleBtn" onclick="toggleEditMode()">
                    <i class="fas fa-edit me-2"></i> <span id="editBtnText">Edit</span>
                </button>
            </div>
        </div>

        <div class="alert alert-warning" id="editModeAlert" style="display: none;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span>You are now in edit mode. Make your changes and click "Update Supply" to save.</span>
        </div>

        <form id="editSupplyForm" action="{{ route('supplies.update', $supply->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-info-circle me-2"></i> Basic Information</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="item_name">Supply Name <span class="required">*</span></label>
                        <input type="text" id="item_name" name="item_name" class="form-control editable-field"
                            value="{{ $supply->item_name }}" readonly required>
                        <div class="invalid-feedback">Supply Name is required</div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity <span class="required">*</span></label>
                        <input type="number" id="quantity" name="quantity" class="form-control editable-field"
                            value="{{ $supply->quantity }}" min="0" readonly required>
                        <div class="invalid-feedback">Quantity is required</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="unit_id">Unit <span class="required">*</span></label>
                        <select id="unit_id" name="unit_id" class="form-select editable-field" disabled required>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ $supply->unit_id == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Unit is required</div>
                    </div>
                    <div class="form-group">
                        <label for="reorder_level">Minimum Stock</label>
                        <input type="number" id="reorder_level" name="reorder_level" class="form-control editable-field"
                            value="{{ $supply->reorder_level }}" min="0" readonly>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-section form-actions" id="formActions" style="display: none;">
                <button type="submit" class="btnn btn-primary">
                    <i class="fas fa-save me-2"></i> Update Supply
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('script/admin/edit-supply.js') }}"></script>
@endsection
