@extends('layouts.staff.layout')

@section('title', 'Update Information - Letty\'s Birthing Home')
@section('page-title', 'Patient Information')

@section('content')

        <div class="container-fluid main-content">
            <div class="form-card">
                <div class="form-header">
                    <h5><i class="fas fa-user-edit me-2"></i>Update Patient Information</h5>
                    <div class="header-actions">
                        <a href="{{ route('patientRecords') }}" class="back-button">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
                <form id="updatePatientForm" action="{{ route('patient.update', $client->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="form-section">
                        <div class="form-section-title"><i class="fas fa-user-circle me-2"></i>Patient Information
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="firstName" name="first_name"
                                    value="{{ old('first_name', $client->first_name) }}" required>
                                <div class="invalid-feedback">First name is required</div>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="lastName" name="last_name"
                                    value="{{ old('last_name', $client->last_name) }}" required>
                                <div class="invalid-feedback">Last name is required</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="village">Barangay <span class="required">*</span></label>
                                <input type="text" class="form-control" id="village" name="village"
                                    value="{{ old('village', $client->address->village ?? '') }}" required>
                                <div class="invalid-feedback">Barangay is required</div>
                            </div>
                            <div class="form-group">
                                <label for="city_municipality">Municipality <span class="required">*</span></label>
                                <input type="text" class="form-control" id="city_municipality"
                                    name="city_municipality"
                                    value="{{ old('city_municipality', $client->address->city_municipality ?? '') }}"
                                    required>
                                <div class="invalid-feedback">Municipality is required</div>
                            </div>
                            <div class="form-group">
                                <label for="province">Province <span class="required">*</span></label>
                                <input type="text" class="form-control" id="province" name="province"
                                    value="{{ old('province', $client->address->province ?? '') }}" required>
                                <div class="invalid-feedback">Province is required</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="client_phone"
                                    value="{{ old('client_phone', $client->client_phone) }}" pattern="09[0-9]{9}"
                                    required>
                                <div class="invalid-feedback">Valid 11-digit phone number is required</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="age">Age <span class="required">*</span></label>
                                <input type="number" class="form-control" id="age" name="age"
                                    value="{{ old('age', $client->patient->age ?? '') }}" min="1"
                                    max="100" required>
                                <div class="invalid-feedback">Age is required</div>
                            </div>
                            <div class="form-group">
                                <label for="maritalStatus">Marital Status <span class="required">*</span></label>
                                <select class="form-select" id="maritalStatus" name="marital_status_id" required>
                                    <option value="" disabled selected>Select marital status</option>
                                    <option value="1"
                                        {{ old('marital_status_id', $client->patient->marital_status_id ?? '') == 1 ? 'selected' : '' }}>
                                        Single</option>
                                    <option value="2"
                                        {{ old('marital_status_id', $client->patient->marital_status_id ?? '') == 2 ? 'selected' : '' }}>
                                        Married</option>
                                    <option value="3"
                                        {{ old('marital_status_id', $client->patient->marital_status_id ?? '') == 3 ? 'selected' : '' }}>
                                        Separated</option>
                                </select>
                                <div class="invalid-feedback">Marital status is required</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="spouseFName">Spouse First Name</label>
                                <input type="text" class="form-control" id="spouseFName" name="spouse_fname"
                                    value="{{ old('spouse_fname', $client->patient->spouse_fname ?? '') }}"
                                    placeholder="Enter spouse first name">
                                <div class="invalid-feedback">Spouse first name is invalid</div>
                            </div>
                            <div class="form-group">
                                <label for="spouseLName">Spouse Last Name</label>
                                <input type="text" class="form-control" id="spouseLName" name="spouse_lname"
                                    value="{{ old('spouse_lname', $client->patient->spouse_lname ?? '') }}"
                                    placeholder="Enter spouse last name">
                                <div class="invalid-feedback">Spouse last name is invalid</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section form-actions">
                        <button type="reset" class="btnn btn-outline-secondary"
                            onclick="clearForm('updatePatientForm')">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                        <button type="submit" class="btnn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Patient
                        </button>
                    </div>
                </form>

            </div>
        </div>
    <script src="{{ asset('script/staff/edit-info.js') }}"></script>
    @endsection
