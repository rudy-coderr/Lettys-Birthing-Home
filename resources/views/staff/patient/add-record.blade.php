@extends('layouts.staff.layout')

@section('title', 'Prenatal Check-up - Letty\'s Birthing Home')
@section('page-title', 'Prenatal Check-up')

@section('content')
    <div class="container-fluid main-content">
        <div class="form-card" id="prenatalSection">
            <div class="form-header">
                <h5><i class="fas fa-plus me-2"></i>Add Prenatal Checkup Record</h5>
                <div class="header-actions">
                    <a href="{{ route('currentPatients') }}" class="back-button"><i class="fas fa-arrow-left me-2"></i>
                        Back</a>
                </div>
            </div>
            <form id="prenatalForm" method="POST" action="{{ route('patient.prenatal.store', $patient->id) }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="prenatal_status_id" id="prenatalStatusId" value="1">

                <div class="form-section">
                    <div class="form-section-title"><i class="fas fa-calendar-check me-2"></i>Visit Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="visitNumber">Visit Number <span class="required">*</span></label>
                            <input type="text" class="form-control" id="visitNumber_display"
                                value="{{ $nextVisitNumber }}{{ $nextVisitNumber == 1 ? 'st' : ($nextVisitNumber == 2 ? 'nd' : ($nextVisitNumber == 3 ? 'rd' : 'th')) }} Visit"
                                readonly>
                            <input type="hidden" name="visit_number" value="{{ $nextVisitNumber }}">
                            <div class="invalid-feedback">Visit number is required</div>
                        </div>
                        <div class="form-group">
                            <label for="visitDate">Date <span class="required">*</span></label>
                            <input type="date" class="form-control" id="visitDate" name="visit_date"
                                value="{{ now()->format('Y-m-d') }}" required>
                            <div class="invalid-feedback">Visit date is required</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nextVisit">Next Visit Date</label>
                            <input type="date" class="form-control" id="nextVisit" name="next_visit_date">
                            <div class="invalid-feedback">Next visit date is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="nextVisitTime">Next Visit Time</label>
                            <input type="time" class="form-control" id="nextVisitTime" name="next_visit_time">
                            <div class="invalid-feedback">Next visit time is invalid</div>
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
                            <input type="text" class="form-control" id="aog" name="aog">
                            <div class="invalid-feedback">AOG is invalid</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gravida">Gravida <span class="required">*</span></label>
                            <input type="number" class="form-control" id="gravida" name="gravida" required>
                            <div class="invalid-feedback">Gravida is required</div>
                        </div>
                        <div class="form-group">
                            <label for="para">Para<span class="required">*</span></label>
                            <input type="number" class="form-control" id="para" name="para" required>
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
                            <input type="number" class="form-control" id="fht" name="fht">
                            <div class="invalid-feedback">FHT is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="fh">Fundal Height - cm</label>
                            <input type="number" class="form-control" id="fh" name="fh">
                            <div class="invalid-feedback">FH is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight - kg</label>
                            <input type="number" class="form-control" id="weight" name="weight">
                            <div class="invalid-feedback">Weight is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="bloodPressure">Blood Pressure</label>
                            <input type="text" class="form-control" id="bloodPressure" name="blood_pressure">
                            <div class="invalid-feedback">Blood Pressure is invalid</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="temperature">Temperature - °C</label>
                            <input type="number" class="form-control" id="temperature" name="temperature">
                            <div class="invalid-feedback">Temperature is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="respiratoryRate">Respiratory Rate</label>
                            <input type="number" class="form-control" id="respiratoryRate" name="respiratory_rate">
                            <div class="invalid-feedback">Respiratory Rate is invalid</div>
                        </div>
                        <div class="form-group">
                            <label for="pulseRate">Pulse Rate</label>
                            <input type="number" class="form-control" id="pulseRate" name="pulse_rate">
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
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"
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

                <div class="form-section hidden-section" id="remarksSection">
                    <div class="form-section-title">
                        <i class="fas fa-sticky-note me-2"></i>Remarks
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="flex: 1 1 100%;">
                            <label for="immunizationNotes">Immunization Notes</label>
                            <textarea class="form-control" id="immunizationNotes" name="immunization_notes" rows="3"
                                placeholder="Enter notes about this immunization..."></textarea>
                        </div>
                    </div>
                </div>



                <div class="form-section form-actions">
                    <button type="submit" class="btnn btn-primary">
                        <i class="fas fa-save me-2"></i> Add Record
                    </button>
                    <button type="submit" class="btnn btn-complete" onclick="setCompleted()">
                        <i class="fas fa-check me-2"></i> Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const vaccines = @json($vaccines);
    </script>

    <script src="{{ asset('script/staff/add-record.js') }}"></script>
@endsection
