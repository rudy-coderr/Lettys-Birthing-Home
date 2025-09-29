@extends('layouts.staff.layout')

@section('title', 'Add Patient Medication - Letty\'s Birthing Home')
@section('page-title', 'Add Patient Medication')

@section('content')

    <div class="container-fluid main-content">
        <div class="form-card">
            <div class="form-header">
                <h5><i class="fas fa-plus me-2"></i>Add Patient Medication</h5>
                <div class="header-actions">
                    <a href="#" class="back-button"><i class="fas fa-arrow-left me-2"></i>
                        Back</a>
                </div>
            </div>
            <form id="billForm" action="{{ route('patientMedication.store') }}" method="POST" novalidate>
                @csrf
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user me-2"></i>Patient Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="patientName">Patient Name <span class="required">*</span></label>
                            <select id="patientName" name="patient_id" class="form-select" required>
                                <option value="" disabled selected>Select a patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}">
                                        {{ $patient->client->last_name }}, {{ $patient->client->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Patient name is required.</div>
                        </div>

                    </div>
                </div>
                <template id="itemOptions">
                    @foreach ($medicines as $med)
                        <option value="{{ $med->id }}" data-type="medicine">{{ $med->item_name }}</option>
                    @endforeach
                    @foreach ($supplies as $sup)
                        <option value="{{ $sup->id }}" data-type="supply">{{ $sup->item_name }}</option>
                    @endforeach
                </template>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-list me-2"></i>Medication Items</div>
                    <table class="appointments-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Medication Type</th>
                                <th>Medication Name</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][type]" class="item-type form-select" required
                                        onchange="updateItemOptions(this)">
                                        <option value="" disabled selected>Select medication type</option>
                                        <option value="medicine">Medicine</option>
                                        <option value="supply">Medical Supply</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="items[0][item_id]" class="item-name form-select" required>
                                        <option value="" disabled selected>Select a medication</option>
                                        @foreach ($medicines as $med)
                                            <option value="{{ $med->id }}" data-type="medicine">
                                                {{ $med->item_name }}</option>
                                        @endforeach
                                        @foreach ($supplies as $sup)
                                            <option value="{{ $sup->id }}" data-type="supply">
                                                {{ $sup->item_name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number" name="items[0][quantity]" class="item-quantity form-control"
                                        required min="1" value="1">
                                </td>
                                <td>
                                    <button type="button" class="remove-item-btn" disabled><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="add-item-btn" onclick="addItemRow()"><i class="fas fa-plus me-2"></i>Add
                        Medication</button>
                </div>
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note me-2"></i>Remarks
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1 1 100%;">
                            <label for="remarks">Notes / Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                placeholder="Enter remarks about this visit..."></textarea>
                            <div class="invalid-feedback">Remarks is invalid</div>
                        </div>
                    </div>
                </div>
                <div class="form-section form-actions">
                    <button type="button" class="btnn btn-danger"><i class="fas fa-times me-2"></i>Cancel</button>
                    <button type="submit" class="btnn btn-primary"><i class="fas fa-save me-2"></i>Save
                        Medication</button>
                </div>


            </form>
        </div>
    </div>
    <script src="{{ asset('script/staff/add-medication.js') }}"></script>
@endsection
