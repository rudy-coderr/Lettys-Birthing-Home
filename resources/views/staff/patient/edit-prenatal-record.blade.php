@extends('layouts.staff.layout')

@section('title', 'Prenatal Record - Letty\'s Birthing Home')
@section('page-title', 'Edit Prenatal Record')

@section('content')
    <div class="container-fluid main-content">
        <div class="form-card" id="prenatalSection">
            <div class="form-header">
                <h5><i class="fas fa-file-medical me-2"></i>Edit Prenatal Record</h5>
                <div class="header-actions">
                    <a href="{{ route('patientRecords') }}" class="back-button"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>
            </div>
            <form id="prenatalForm"
                action="{{ isset($record) ? route('updatePrenatal', $record->id) : route('storePrenatal') }}" method="POST"
                novalidate>
                @csrf
                @if (isset($record))
                    @method('PUT')
                @endif

                {{-- Patient Information --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user-circle me-2"></i>Patient Information</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', $client->first_name ?? '') }}" required>
                            <div class="invalid-feedback">First name is required</div>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', $client->last_name ?? '') }}" required>
                            <div class="invalid-feedback">Last name is required</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="village">Barangay <span class="required">*</span></label>
                            <input type="text" class="form-control" id="village" name="village"
                                value="{{ old('village', $address->village ?? '') }}" required>
                            <div class="invalid-feedback">Barangay is required</div>
                        </div>
                        <div class="form-group">
                            <label for="city_municipality">Municipality <span class="required">*</span></label>
                            <input type="text" class="form-control" id="city_municipality" name="city_municipality"
                                value="{{ old('city_municipality', $address->city_municipality ?? '') }}" required>
                            <div class="invalid-feedback">Municipality is required</div>
                        </div>
                        <div class="form-group">
                            <label for="province">Province <span class="required">*</span></label>
                            <input type="text" class="form-control" id="province" name="province"
                                value="{{ old('province', $address->province ?? '') }}" required>
                            <div class="invalid-feedback">Province is required</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $client->client_phone ?? '') }}" pattern="09[0-9]{9}" required>
                            <div class="invalid-feedback">Valid 11-digit phone number is required</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age <span class="required">*</span></label>
                            <input type="number" class="form-control" id="age" name="age"
                                value="{{ old('age', $client->patient->age ?? '') }}" min="1" max="100"
                                required>
                            <div class="invalid-feedback">Age is required</div>
                        </div>
                        <div class="form-group">
                            <label for="maritalStatus">Marital Status <span class="required">*</span></label>
                            <select class="form-select" id="maritalStatus" name="marital_status" required>
                                <option value="" disabled>Select marital status</option>
                                <option value="1"
                                    {{ old('marital_status', $client->patient->marital_status_id ?? '') == 1 ? 'selected' : '' }}>
                                    Single</option>
                                <option value="2"
                                    {{ old('marital_status', $client->patient->marital_status_id ?? '') == 2 ? 'selected' : '' }}>
                                    Married</option>
                                <option value="3"
                                    {{ old('marital_status', $client->patient->marital_status_id ?? '') == 3 ? 'selected' : '' }}>
                                    Separated</option>
                            </select>
                            <div class="invalid-feedback">Marital status is required</div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="spouseFName">Spouse First Name</label>
                            <input type="text" class="form-control" id="spouseFName" name="spouse_fname"
                                value="{{ old('spouse_fname', $client->patient->spouse_fname ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="spouseLName">Spouse Last Name</label>
                            <input type="text" class="form-control" id="spouseLName" name="spouse_lname"
                                value="{{ old('spouse_lname', $client->patient->spouse_lname ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- Pregnancy Details --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-baby me-2"></i>Pregnancy Details</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="lmp">Last Menstrual Period</label>
                            <input type="date" class="form-control" id="lmp" name="lmp"
                                value="{{ old('lmp', $prenatalVisit->lmp ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="edc">Estimated Date of Confinement</label>
                            <input type="date" class="form-control" id="edc" name="edc"
                                value="{{ old('edc', $prenatalVisit->edc ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="aog">Age of Gestation - weeks</label>
                            <input type="text" class="form-control" id="aog" name="aog"
                                value="{{ old('aog', $prenatalVisit->aog ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="gravida">Gravida <span class="required">*</span></label>
                            <input type="number" class="form-control" id="gravida" name="gravida"
                                value="{{ old('gravida', $prenatalVisit->gravida ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="para">Para <span class="required">*</span></label>
                            <input type="number" class="form-control" id="para" name="para"
                                value="{{ old('para', $prenatalVisit->para ?? '') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Maternal Vitals --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-heartbeat me-2"></i>Maternal Vital Signs</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fht">Fetal Heart Tones - bpm</label>
                            <input type="number" class="form-control" id="fht" name="fht"
                                value="{{ old('fht', $maternalVitals->fht ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="fh">Fundal Height - cm</label>
                            <input type="number" class="form-control" id="fh" name="fh"
                                value="{{ old('fh', $maternalVitals->fh ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight - kg</label>
                            <input type="number" class="form-control" id="weight" name="weight"
                                value="{{ old('weight', $maternalVitals->weight ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="blood_pressure">Blood Pressure</label>
                            <input type="text" class="form-control" id="blood_pressure" name="blood_pressure"
                                value="{{ old('blood_pressure', $maternalVitals->blood_pressure ?? '') }}">
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="temperature">Temperature - °C</label>
                            <input type="number" class="form-control" id="temperature" name="temperature"
                                value="{{ old('temperature', $maternalVitals->temperature ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="respiratoryRate">Respiratory Rate</label>
                            <input type="number" class="form-control" id="respiratoryRate" name="respiratoryRate"
                                value="{{ old('respiratoryRate', $maternalVitals->respiratory_rate ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="pulseRate">Pulse Rate</label>
                            <input type="number" class="form-control" id="pulseRate" name="pulseRate"
                                value="{{ old('pulseRate', $maternalVitals->pulse_rate ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- Remarks --}}
                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-sticky-note me-2"></i>Remarks</div>
                    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $prenatalVisit->remarks->notes ?? '') }}</textarea>
                </div>


                {{-- Actions --}}
                <div class="form-section form-actions">
                    <button type="reset" class="btnn btn-outline-secondary" onclick="clearForm('prenatalForm')">
                        <i class="fas fa-undo me-2"></i>Reset
                    </button>
                    <button type="submit" class="btnn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Records
                    </button>
                </div>
            </form>

        </div>

    </div>



    <script src="{{ asset('script/staff/first-prenatal-checkup.js') }}"></script>
@endsection
