@extends('layouts.staff.layout')

@section('title', 'Add Appointment - Letty\'s Birthing Home')
@section('page-title', 'Appointment Management')

@section('content')
<style>
/* Time Slot Information Styling */
.time-slot-info {
    margin-top: 8px;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    animation: fadeIn 0.3s ease-in-out;
}
.time-slot-info.loading {
    background-color: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}
.time-slot-info.success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}
.time-slot-info.warning {
    background-color: #fff3e0;
    color: #f57c00;
    border: 1px solid #ffe0b2;
}
.time-slot-info.error {
    background-color: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}
.fa-spinner.fa-spin {
    animation: spin 1s linear infinite;
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
#appointmentTime:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>

    <div class="container-fluid main-content">
        <div class="form-card" id="appointmentSection">
            <div class="form-header">
                <h5>
                    <i class="fas fa-calendar-plus me-2"></i>
                    Add New Appointment
                </h5>
                <div class="header-actions">
                    <a href="{{ route('todaysAppointments') }}" class="back-button">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>
            <form id="addAppointmentForm" method="POST" action="{{ route('storeAppointment') }}">
                @csrf
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-user-circle me-2"></i>
                        Patient Information
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', $client->first_name ?? '') }}" placeholder="Enter First Name"
                                required>
                            <div class="invalid-feedback">Please enter a valid first name.</div>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', $client->last_name ?? '') }}" placeholder="Enter Last Name"
                                required>
                            <div class="invalid-feedback">Please enter a valid last name.</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phoneNumber">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-control" id="phoneNumber" name="client_phone"
                                value="{{ old('client_phone', $client->client_phone ?? '') }}" placeholder="09123456789"
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
                            <label for="branch">Branch <span class="required">*</span></label>
                            <select class="form-select" id="branch" name="branch" required>
                                <option value="" disabled selected>Select Branch</option>
                                <option value="Sta. Justina" data-branch-id="1">Sta. Justina</option>
                                <option value="San Pedro" data-branch-id="2">San Pedro</option>
                            </select>
                            <div class="invalid-feedback">Please select a branch.</div>
                        </div>
                        <div class="form-group">
                            <label for="appointmentDate">Appointment Date <span class="required">*</span></label>
                            <input type="date" class="form-control" id="appointmentDate" name="appointment_date"
                                min="{{ date('Y-m-d') }}" required>
                            <div class="invalid-feedback">Please select a valid date.</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="appointmentTime">Appointment Time <span class="required">*</span></label>
                            <select class="form-select" id="appointmentTime" name="appointment_time" required disabled>
                                <option value="" disabled selected>Select date and branch first</option>
                            </select>
                            <div class="invalid-feedback">Please select a valid time.</div>
                            <div id="timeSlotHelp" class="time-slot-info" style="display: none;">
                                <i class="fas fa-info-circle"></i> Loading available time slots...
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reason">Reason <span class="required">*</span></label>
                            <textarea class="form-textarea" id="reason" name="reason" placeholder="Enter Reason for Appointment" required></textarea>
                            <div class="invalid-feedback">Please enter a valid reason.</div>
                        </div>
                    </div>
                </div>
                <div class="form-section form-actions">
                    <button type="submit" class="btnn btn-primary">
                        <i class="fas fa-save me-2"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>

    <script>
        const availableSlotsUrl = "{{ route('appointments.availableSlots') }}";
    </script>
    <script src="{{ asset('script/staff/add-appointment.js') }}"></script>
@endsection