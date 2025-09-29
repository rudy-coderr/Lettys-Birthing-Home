@extends('layouts.admin.layout')

@section('title', 'Add New Medicine - Letty\'s Birthing Home')
@section('page-title', 'Inventory Management')

@section('content')
<div class="container-fluid main-content">
    <div class="form-card" id="medicineSection">
        <div class="form-header">
            <h5><i class="fas fa-plus-circle me-2"></i> Add New Medicine</h5>
            <div class="header-actions">
                <a href="{{ route('admin.inventory.medicines') }}" class="back-button">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>

        <!-- Form Start -->
        <form id="addMedicineForm" method="POST" action="{{ route('medicines.store') }}" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="type" value="medicine">

            <!-- Basic Info -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-info-circle me-2"></i> Basic Information</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Medicine Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="e.g., Paracetamol" required>
                        <div class="invalid-feedback">Medicine Name is required</div>
                    </div>
                    <div class="form-group">
                        <label>Batch Number</label>
                        <input type="text" class="form-control" name="batch_no" placeholder="e.g., BTH123456">
                    </div>
                </div>
            </div>

            <!-- Inventory Details -->
            <div class="form-section">
                <div class="form-section-title"><i class="fas fa-warehouse me-2"></i> Inventory Details</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Initial Quantity <span class="required">*</span></label>
                        <input type="number" class="form-control" name="quantity" placeholder="0" min="0" required>
                        <div class="invalid-feedback">Initial Quantity is required</div>
                    </div>
                    <div class="form-group">
                        <label>Unit <span class="required">*</span></label>
                        <select class="form-select" name="unit_id" required>
                            <option value="" disabled selected>Select unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Unit is required</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" class="form-control" name="expiry_date">
                    </div>
                    <div class="form-group">
                        <label>Minimum Stock Level</label>
                        <input type="number" class="form-control" name="reorder_level" placeholder="0" min="0">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-section form-actions">
                <button type="submit" class="btnn btn-primary">
                    <i class="fas fa-save me-2"></i> Save Medicine
                </button>
            </div>
        </form>
    </div>
</div>

 <script src="{{ asset('script/admin/add-medicine.js') }}"></script>
@endsection
