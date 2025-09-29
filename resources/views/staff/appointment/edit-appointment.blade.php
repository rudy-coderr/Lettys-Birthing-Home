@extends('layouts.staff.layout')

@section('title', 'Edit Appointment - Letty\'s Birthing Home')
@section('page-title', 'Edit Appointment')

@section('content')


        <div class="container-fluid main-content">
            <div class="form-card" id="appointmentSection">
                <div class="form-header">
                    <h5>
                        <i class="fas fa-calendar-edit me-2"></i>
                        Edit Appointment
                    </h5>
                    <div class="header-actions">
                        <a href="{{ route('todaysAppointments') }}" class="back-button">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
                <form id="editAppointmentForm" method="POST"
                    action="{{ route('updateAppointment', ['id' => $appointment->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user-circle me-2"></i>
                            Patient Information
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="firstName" name="first_name"
                                    value="{{ $appointment->client->first_name }}" placeholder="Enter First Name"
                                    required>
                                <div class="invalid-feedback">Please enter a valid first name.</div>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="lastName" name="last_name"
                                    value="{{ $appointment->client->last_name }}" placeholder="Enter Last Name"
                                    required>
                                <div class="invalid-feedback">Please enter a valid last name.</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number <span class="required">*</span></label>
                                <input type="tel" class="form-control" id="phoneNumber" name="client_phone"
                                    value="{{ $appointment->client->client_phone }}" placeholder="09123456789"
                                    pattern="09[0-9]{9}" required>
                                <div class="invalid-feedback">Please enter a valid phone number starting with 09.</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-calendar-check me-2"></i>
                            Appointment Details
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="appointmentDate">Appointment Date <span class="required">*</span></label>
                                <input type="date" class="form-control" id="appointmentDate"
                                    name="appointment_date" value="{{ $appointment->appointment_date }}" required>
                                <div class="invalid-feedback">Please select a valid date.</div>
                            </div>
                            <div class="form-group">
                                <label for="appointmentTime">Appointment Time <span class="required">*</span></label>
                                <input type="time" class="form-control" id="appointmentTime"
                                    name="appointment_time" value="{{ $appointment->appointment_time }}" required>
                                <div class="invalid-feedback">Please select a valid time.</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="branch">Branch <span class="required">*</span></label>
                                <select class="form-select" id="branch" name="branch" required>
                                    <option value="" disabled>Select Branch</option>
                                    <option value="Sta. Justina"
                                        {{ $appointment->branch->branch_name == 'Sta. Justina' ? 'selected' : '' }}>
                                        Sta. Justina</option>
                                    <option value="San Pedro"
                                        {{ $appointment->branch->branch_name == 'San Pedro' ? 'selected' : '' }}>
                                        San Pedro</option>
                                </select>
                                <div class="invalid-feedback">Please select a branch.</div>
                            </div>
                            <div class="form-group">
                                <label for="reason">Reason <span class="required">*</span></label>
                                <textarea class="form-textarea" id="reason" name="reason" placeholder="Enter Reason for Appointment" required>{{ $appointment->appointment_reason }}</textarea>
                                <div class="invalid-feedback">Please enter a valid reason.</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section form-actions">
                        <button type="submit" class="btnn btn-primary">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

          <script src="{{ asset('script/staff/edit-appointment.js') }}"></script>
    @endsection

