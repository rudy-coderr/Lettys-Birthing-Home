@extends('layouts.staff.layout')

@section('title', 'Prenatal Check-up - Letty\'s Birthing Home')
@section('page-title', 'Prenatal Check-up')

@section('content')
    <div class="container-fluid main-content">
        <div class="form-card" id="prenatalSection">
            <div class="form-header">
                <h5><i class="fas fa-file-medical me-2"></i>Prenatal Check-up</h5>
                <div class="header-actions">
                    <a href="{{ route('patientRecords') }}" class="back-button"><i class="fas fa-arrow-left me-2"></i>Back</a>
                </div>
            </div>
            <form id="prenatalForm" action="{{ route('prenatal.store') }}" method="POST">
                @csrf
                @if (isset($appointment))
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                @endif
                <input type="hidden" name="prenatal_status_id" id="prenatalStatusId" value="1">

                @php
                    $visitNumber = 1;
                    $suffix = 'st';

                    if (isset($appointment->client)) {
                        $latestVisit = $appointment->client->prenatalVisits->sortByDesc('created_at')->first();

                        if ($latestVisit && $latestVisit->prenatal_status_id != 2) {
                            $visitNumber = $appointment->client->prenatalVisits->count() + 1;
                        }

                        // Set suffix
                        if ($visitNumber == 1) {
                            $suffix = 'st';
                        } elseif ($visitNumber == 2) {
                            $suffix = 'nd';
                        } elseif ($visitNumber == 3) {
                            $suffix = 'rd';
                        } else {
                            $suffix = 'th';
                        }
                    }
                @endphp

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-calendar-check me-2"></i>Visit Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="visitNumber">Visit Number</label>
                            <input type="text" class="form-control" id="visitNumber" name="visit_number"
                                value="{{ $visitNumber }}{{ $suffix }} Visit" readonly>
                            <div class="invalid-feedback">Visit number is required</div>
                        </div>
                        <div class="form-group">
                            <label for="visitDate">Date <span class="required">*</span></label>
                            <input type="date" class="form-control" id="visitDate" name="visitDate" required>
                            <div class="invalid-feedback">Visit date is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nextVisit">Next Visit Date</label>
                            <input type="date" class="form-control" id="nextVisit" name="nextVisit">
                            <div class="invalid-feedback">Next visit date is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="nextVisitTime">Next Visit Time</label>
                            <input type="time" class="form-control" id="nextVisitTime" name="nextVisitTime">
                            <div class="invalid-feedback">Next visit time is invalid</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-user-circle me-2"></i>Patient Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', $appointment->client->first_name ?? '') }}" required>
                            <div class="invalid-feedback">First name is required</div>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', $appointment->client->last_name ?? '') }}" required>
                            <div class="invalid-feedback">Last name is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="village">Barangay <span class="required">*</span></label>
                            <input type="text" class="form-control" id="village" name="village"
                                value="{{ old('village', $appointment->client->village ?? '') }}" required>
                            <div class="invalid-feedback">Barangay is required</div>
                        </div>
                        <div class="form-group">
                            <label for="city_municipality">Municipality <span class="required">*</span></label>
                            <input type="text" class="form-control" id="city_municipality" name="city_municipality"
                                required>
                            <div class="invalid-feedback">Municipality is required</div>
                        </div>
                        <div class="form-group">
                            <label for="province">Province <span class="required">*</span></label>
                            <input type="text" class="form-control" id="province" name="province" required>
                            <div class="invalid-feedback">Province is required</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $appointment->client->client_phone ?? '') }}" pattern="09[0-9]{9}"
                                required>
                            <div class="invalid-feedback">Valid 11-digit phone number is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age <span class="required">*</span></label>
                            <input type="number" class="form-control" id="age" name="age" min="1"
                                max="100" required>
                            <div class="invalid-feedback">Age is required</div>
                        </div>
                        <div class="form-group">
                            <label for="maritalStatus">Marital Status <span class="required">*</span></label>
                            <select class="form-select" id="maritalStatus" name="marital_status" required>
                                <option value="" disabled selected>Select marital status</option>
                                <option value="1"
                                    {{ old('marital_status', $appointment->client->patient->marital_status_id ?? '') == 1 ? 'selected' : '' }}>
                                    Single</option>
                                <option value="2"
                                    {{ old('marital_status', $appointment->client->patient->marital_status_id ?? '') == 2 ? 'selected' : '' }}>
                                    Married</option>
                                <option value="3"
                                    {{ old('marital_status', $appointment->client->patient->marital_status_id ?? '') == 3 ? 'selected' : '' }}>
                                    Separated</option>
                            </select>
                            <div class="invalid-feedback">Marital status is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="spouseFName">Spouse First Name</label>
                            <input type="text" class="form-control" id="spouseFName" name="spouse_fname"
                                value="{{ old('spouse_fname', $appointment->client->spouse_fname ?? '') }}"
                                placeholder="Enter spouse first name">
                            <div class="invalid-feedback">Spouse first name is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="spouseLName">Spouse Last Name</label>
                            <input type="text" class="form-control" id="spouseLName" name="spouse_lname"
                                value="{{ old('spouse_lname', $appointment->client->spouse_lname ?? '') }}"
                                placeholder="Enter spouse last name">
                            <div class="invalid-feedback">Spouse last name is invalid</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-baby me-2"></i>Pregnancy Details</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="lmp">Last Menstrual Period</label>
                            <input type="date" class="form-control" id="lmp" name="lmp">
                            <div class="invalid-feedback">LMP is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="edc">Estimated Date of Confinement</label>
                            <input type="date" class="form-control" id="edc" name="edc">
                            <div class="invalid-feedback">EDC is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="aog">Age of Gestation - weeks</label>
                            <input type="text" class="form-control" id="aog" name="aog"
                                placeholder="e.g., 28">
                            <div class="invalid-feedback">AOG is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="gravida">Gravida <span class="required">*</span></label>
                            <input type="number" class="form-control" id="gravida" name="gravida"
                                placeholder="e.g., 3" required>
                            <div class="invalid-feedback">Gravida is required</div>
                        </div>
                        <div class="form-group">
                            <label for="para">Para <span class="required">*</span></label>
                            <input type="number" class="form-control" id="para" name="para"
                                placeholder="e.g., 2" required>
                            <div class="invalid-feedback">Para is required</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-heartbeat me-2"></i>Maternal Vital Signs &
                        Physical Exam</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fht">Fetal Heart Tones - bpm</label>
                            <input type="number" class="form-control" id="fht" name="fht" placeholder="140">
                            <div class="invalid-feedback">FHT is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="fh">Fundal Height - cm</label>
                            <input type="number" class="form-control" id="fh" name="fh" placeholder="28"
                                step="0.1">
                            <div class="invalid-feedback">FH is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight - kg</label>
                            <input type="number" class="form-control" id="weight" name="weight" placeholder="65"
                                step="0.1">
                            <div class="invalid-feedback">Weight is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="bloodPressure">Blood Pressure</label>
                            <input type="text" class="form-control" id="bloodPressure" name="bloodPressure"
                                placeholder="120/80">
                            <div class="invalid-feedback">Blood Pressure is invalid</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="temperature">Temperature - °C</label>
                            <input type="number" class="form-control" id="temperature" name="temperature"
                                placeholder="36.5" step="0.1">
                            <div class="invalid-feedback">Temperature is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="respiratoryRate">Respiratory Rate</label>
                            <input type="number" class="form-control" id="respiratoryRate" name="respiratoryRate"
                                placeholder="20">
                            <div class="invalid-feedback">Respiratory Rate is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="pulseRate">Pulse Rate</label>
                            <input type="number" class="form-control" id="pulseRate" name="pulseRate"
                                placeholder="80">
                            <div class="invalid-feedback">Pulse Rate is invalid</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note me-2"></i>Remarks
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1 1 100%;">
                            <label for="remarks">Notes / Remarks</label>
                            <textarea class="form-textarea form-control" id="remarks" name="remarks" rows="3"
                                placeholder="Enter remarks about this visit..."></textarea>
                            <div class="invalid-feedback">Remarks is invalid</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-row">
                        <div class="form-group">
                            <div>
                                <input type="checkbox" id="showImmunizationSection" name="show_immunization">
                                <label for="showImmunizationSection" class="ms-2">Add Immunization
                                    Transaction</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section hidden-section" id="immunizationSection">
                    <div class="form-section-title">
                        <i class="fas fa-syringe me-2"></i>Immunization Details
                    </div>
                    <div id="immunizationEntries">
                        <!-- Dynamic vaccine entries will be added here -->
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <button type="button" class="btnn btn-primary" id="addVaccineBtn">
                                <i class="fas fa-plus me-2"></i>Add Vaccine
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-section hidden-section" id="immunizationRemarksSection">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note me-2"></i>Immunization Remarks
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1 1 100%;">
                            <label for="immunizationNotes">Immunization Notes</label>
                            <textarea class="form-textarea form-control" id="immunizationNotes" name="immunization_notes" rows="3"
                                placeholder="Enter notes about this immunization..."></textarea>
                            <div class="invalid-feedback">Immunization notes are invalid</div>
                        </div>
                    </div>
                </div>

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


    <script>
        const vaccines = @json($vaccines);
    </script>

    <script src="{{ asset('script/staff/first-prenatal-checkup.js') }}"></script>
@endsection
