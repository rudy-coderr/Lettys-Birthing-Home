@extends('layouts.staff.layout')

@section('title', 'Today\'s Appointments - Letty\'s Birthing Home')
@section('page-title', 'Appointment Management')
@section('content')
    <div class="container-fluid main-content">
        <div class="appointments-card">
            <div class="appointments-header">
                <h5>
                    <i class="fas fa-calendar-check me-2"></i>Today's Appointments
                </h5>
                <a href="{{ route('addAppointment') }}" class="add-appointment-btn" aria-label="Add new appointment">
                    <i class="fas fa-plus me-2"></i>Add Appointment
                </a>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputAll" placeholder="Search appointments..."
                        oninput="searchAppointments('all')">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('reasonFilterAll')">
                        <span>Reason</span>
                        <div class="d-flex align-items-center">
                            <span id="reasonFilterCountAll" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="reasonFilterAll">
                        <div class="filter-option selected" onclick="filterAppointments('reason', 'all', 'all')">All Reasons
                        </div>
                        <div class="filter-option" onclick="filterAppointments('reason', 'checkup', 'all')">Checkup</div>
                        <div class="filter-option" onclick="filterAppointments('reason', 'consultation', 'all')">
                            Consultation</div>
                        <div class="filter-option" onclick="filterAppointments('reason', 'follow-up', 'all')">Follow-Up
                        </div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnAll" style="display: none;"
                    onclick="clearAllFilters('all')">
                    Clear All Filters
                </button>
            </div>

            <div class="table-container">
                <table class="appointments-table" id="appointmentsTableAll">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Reason</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr data-id="{{ $appointment->id }}">
                                <td class="patient-first-name">{{ $appointment->client->first_name ?? 'N/A' }}</td>
                                <td class="patient-last-name">{{ $appointment->client->last_name ?? 'N/A' }}</td>
                                <td class="patient-phone">{{ $appointment->client->client_phone ?? 'N/A' }}</td>
                                <td class="appointment-reason">{{ $appointment->appointment_reason ?? 'N/A' }}</td>
                                <td class="schedule-info">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('F d, Y, g:ia') }}
                                </td>
                                <td class="actions-cell">
                                    <!-- Prenatal Button -->
                                    <a href="{{ route('checkups', ['id' => $appointment->id]) }}"
                                        class="action-btn add-record-btn" title="Add Prenatal Record">
                                        <i class="fas fa-stethoscope"></i>
                                    </a>
                                    <a href="{{ route('patient.postnatalCare', ['id' => $appointment->client_id]) }}"
                                        class="action-btn add-record-btn" title="Add Postnatal Record"
                                        style="color: #28a745;">
                                        <i class="fas fa-baby"></i>
                                    </a>


                                </td>

                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="6" class="text-center">No appointments available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="noResultsAll" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No appointments found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="appointmentsPaginationAll">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="itemsPerPageAll" onchange="updateItemsPerPage('all')">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="prevPageAll" onclick="changePage('all', -1)"
                        disabled>Previous</button>
                    <span id="pageNumbersAll"></span>
                    <button class="pagination-btn" id="nextPageAll" onclick="changePage('all', 1)">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>

    <script src="{{ asset('script/staff/todays-appointment.js') }}"></script>
@endsection
