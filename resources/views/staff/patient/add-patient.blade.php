@extends('layouts.staff.layout')

@section('title', 'Walkin Patient - Letty\'s Birthing Home')
@section('page-title', 'Add Patient')

@section('content')

    <div class="container-fluid main-content">
        <form id="patientForm" method="POST" action="{{ route('storePatientRecord') }}" enctype="multipart/form-data">
            @csrf

            <!-- Patient Information Section -->
            <div class="form-card" id="patientSection">
                <div class="form-header">
                    <h5>
                        <i class="fas fa-user-plus me-2"></i>
                        Add Patient Information
                    </h5>
                    <div class="header-actions">
                        <a href="{{ route('currentPatients') }}" class="back-button">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-user-circle me-2"></i>
                        Patient Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                            <div class="invalid-feedback">First name is required</div>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                            <div class="invalid-feedback">Last name is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="barangay">Barangay <span class="required">*</span></label>
                            <input type="text" class="form-control" id="barangay" name="barangay" required>
                            <div class="invalid-feedback">Barangay is required</div>
                        </div>
                        <div class="form-group">
                            <label for="municipality">Municipality <span class="required">*</span></label>
                            <input type="text" class="form-control" id="municipality" name="municipality" required>
                            <div class="invalid-feedback">Municipality is required</div>
                        </div>
                        <div class="form-group">
                            <label for="province">Province <span class="required">*</span></label>
                            <input type="text" class="form-control" id="province" name="province" required>
                            <div class="invalid-feedback">Province is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" pattern="09[0-9]{9}"
                                required>
                            <div class="invalid-feedback">Valid 11-digit phone number is required</div>
                        </div>
                        <div class="form-group">
                            <label for="age">Age <span class="required">*</span></label>
                            <input type="number" class="form-control" id="age" name="age" required>
                            <div class="invalid-feedback">Age is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="maritalStatus">Marital Status <span class="required">*</span></label>
                            <select class="form-select" id="maritalStatus" name="marital_status_id" required>
                                <option value="" disabled selected>Select marital status</option>
                                <option value="1">Single</option>
                                <option value="2">Married</option>
                                <option value="3">Separated</option>
                            </select>
                            <div class="invalid-feedback">Marital status is required</div>
                        </div>
                        <div class="form-group">
                            <label for="spouseFName">Spouse First Name</label>
                            <input type="text" class="form-control" id="spouseFName" name="spouse_fname"
                                placeholder="Enter spouse first name">
                            <div class="invalid-feedback">Spouse first name is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="spouseLName">Spouse Last Name</label>
                            <input type="text" class="form-control" id="spouseLName" name="spouse_lname"
                                placeholder="Enter spouse last name">
                            <div class="invalid-feedback">Spouse last name is invalid</div>
                        </div>
                    </div>
                </div>

                <div class="form-section form-actions">
                    <button type="button" class="btnn btn-secondary" onclick="nextStep()">
                        <i class="fas fa-arrow-right me-2"></i> Next
                    </button>
                </div>
            </div>

            <!-- Prenatal Checkup Section -->
            <div class="form-card section-hidden" id="prenatalSection">
                <div class="form-header">
                    <h5>
                        <i class="fas fa-plus me-2"></i>
                        Add Prenatal Checkup Record
                    </h5>
                    <div class="header-actions">
                        <button type="button" class="back-button" onclick="showPatientSection()">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </button>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-calendar-check me-2"></i>
                        Visit Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="visitNumber">Visit Number</label>
                            <input type="number" class="form-control" id="visitNumber" name="visit_number"
                                value="1" readonly>
                        </div>
                        <div class="form-group">
                            <label for="visitDate">Date</label>
                            <input type="date" class="form-control" id="visitDate" name="visit_date"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nextVisitDate">Next Visit Date</label>
                            <input type="date" class="form-control" id="nextVisitDate" name="next_visit_date">
                            <div class="invalid-feedback">Next visit date is required</div>
                        </div>
                        <div class="form-group">
                            <label for="nextVisitTime">Next Visit Time</label>
                            <input type="time" class="form-control" id="nextVisitTime" name="next_visit_time">
                            <div class="invalid-feedback">Next visit time is required</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-baby me-2"></i>
                        Pregnancy Details
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="lmp">Last Menstrual Period</label>
                            <input type="date" class="form-control" id="lmp" name="lmp">
                            <div class="invalid-feedback">LMP is required</div>
                        </div>
                        <div class="form-group">
                            <label for="edc">Estimated Date of Confinement</label>
                            <input type="date" class="form-control" id="edc" name="edc">
                            <div class="invalid-feedback">EDC is required</div>
                        </div>
                        <div class="form-group">
                            <label for="aog">Age of Gestation</label>
                            <input type="text" class="form-control" id="aog" name="aog">
                            <div class="invalid-feedback">AOG is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gravida">Gravida</label>
                            <input type="number" class="form-control" id="gravida" name="gravida">
                            <div class="invalid-feedback">Gravida is required</div>
                        </div>
                        <div class="form-group">
                            <label for="para">Para</label>
                            <input type="number" class="form-control" id="para" name="para">
                            <div class="invalid-feedback">Para is required</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-heartbeat me-2"></i>
                        Maternal Vital Signs & Physical Exam
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fht">Fetal Heart Tones</label>
                            <input type="text" class="form-control" id="fht" name="fht">
                            <div class="invalid-feedback">FHT is required</div>
                        </div>
                        <div class="form-group">
                            <label for="fh">Fundal Height</label>
                            <input type="text" class="form-control" id="fh" name="fh">
                            <div class="invalid-feedback">FH is required</div>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input type="text" class="form-control" id="weight" name="weight">
                            <div class="invalid-feedback">Weight is required</div>
                        </div>
                        <div class="form-group">
                            <label for="bp">Blood Pressure</label>
                            <input type="text" class="form-control" id="bp" name="bp">
                            <div class="invalid-feedback">Blood pressure is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="temp">Temperature</label>
                            <input type="text" class="form-control" id="temp" name="temp">
                            <div class="invalid-feedback">Temperature is required</div>
                        </div>
                        <div class="form-group">
                            <label for="rr">Respiratory Rate</label>
                            <input type="text" class="form-control" id="rr" name="rr">
                            <div class="invalid-feedback">Respiratory rate is required</div>
                        </div>
                        <div class="form-group">
                            <label for="pr">Pulse Rate</label>
                            <input type="text" class="form-control" id="pr" name="pr">
                            <div class="invalid-feedback">Pulse rate is required</div>
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
                            <textarea class="form-control form-textarea" id="remarks" name="remarks" rows="3"
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
                                <label for="showImmunizationSection" class="ms-2">Add Immunization Transaction</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section section-hidden" id="immunizationSection">
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

                <div class="form-section section-hidden" id="immunizationRemarksSection">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note me-2"></i>Immunization Remarks
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1 1 100%;">
                            <label for="immunizationNotes">Immunization Notes</label>
                            <textarea class="form-control form-textarea" id="immunizationNotes" name="immunization_notes" rows="3"
                                placeholder="Enter notes about this immunization..."></textarea>
                            <div class="invalid-feedback">Immunization notes are invalid</div>
                        </div>
                    </div>
                </div>

                <div class="form-section form-actions">
                    <button type="submit" class="btnn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Record
                    </button>
                    <button type="button" class="btnn btn-danger" onclick="cancelForm()">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        const vaccines = @json($vaccines);
    </script>

    <script src="{{ asset('script/staff/add-patient.js') }}"></script>
@endsection
