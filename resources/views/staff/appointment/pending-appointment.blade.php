@extends('layouts.staff.layout')

@section('title', 'All Appointments - Letty\'s Birthing Home')
@section('page-title', 'Appointment Management')

@section('content')
    <div class="container-fluid main-content">
        <div class="appointments-card">
            <div class="appointments-header">
                <h5>
                    <i class="fas fa-calendar-check me-2"></i>All Appointments
                </h5>
                <a href="{{ route('addAppointment') }}" class="add-appointment-btn" aria-label="Add new appointment">
                    <i class="fas fa-plus me-2"></i>Add Appointment
                </a>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputConfirmed" placeholder="Search confirmed appointments..."
                        oninput="searchAppointments('confirmed')">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('statusFilterConfirmed')">
                        <span>Status</span>
                        <div class="d-flex align-items-center">
                            <span id="statusFilterCountConfirmed" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="statusFilterConfirmed">
                        <div class="filter-option selected" onclick="filterAppointments('status', 'all', 'confirmed')">All
                            Statuses</div>
                        <div class="filter-option" onclick="filterAppointments('status', 'confirmed', 'confirmed')">Confirmed
                        </div>
                        <div class="filter-option" onclick="filterAppointments('status', 'no-show', 'confirmed')">No-show
                        </div>
                        <div class="filter-option" onclick="filterAppointments('status', 'cancelled', 'confirmed')">Cancelled
                        </div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnConfirmed" style="display: none;"
                    onclick="clearAllFilters('confirmed')">
                    Clear All Filters
                </button>
            </div>

            <div class="table-container">
                <table class="appointments-table" id="confirmedAppointmentsTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Reason</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr data-id="AP{{ $appointment->id }}">
                                <td class="appointment-first-name">{{ $appointment->client->first_name ?? 'N/A' }}</td>
                                <td class="appointment-last-name">{{ $appointment->client->last_name ?? 'N/A' }}</td>
                                <td class="appointment-phone">{{ $appointment->client->client_phone ?? 'N/A' }}</td>
                                <td class="appointment-reason">{{ $appointment->appointment_reason ?? 'N/A' }}</td>
                                <td class="appointment-schedule">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('F d, Y, g:ia') }}
                                </td>
                                <td
                                    class="appointment-status status-{{ strtolower($appointment->appointment_status->status_name ?? 'unknown') }}">
                                    {{ $appointment->appointment_status->status_name ?? 'N/A' }}
                                </td>
                                <td class="actions-cell">
                                    <!-- Edit Button -->
                                    <a href="{{ route('editAppointment', $appointment->id) }}" class="action-btn edit-btn"
                                        title="Edit Appointment">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Reschedule Button -->
                                    <button class="action-btn resched-btn" title="Reschedule"
                                        data-id="{{ $appointment->id }}" data-date="{{ $appointment->appointment_date }}"
                                        data-time="{{ $appointment->appointment_time }}"
                                        onclick="openRescheduleModal(this)">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>

                                    <!-- Cancel Button -->
                                    <button class="action-btn cancel-btn" title="Cancel Appointment"
                                        data-id="{{ $appointment->id }}"
                                        onclick="openCancelModal('{{ $appointment->id }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="7" class="text-center">
                                    No confirmed, no-show, or cancelled appointments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div id="noResultsConfirmed" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No appointments found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="confirmedAppointmentsPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="confirmedItemsPerPage" onchange="updateItemsPerPage('confirmed')">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="confirmedPrevPage" onclick="changePage('confirmed', -1)"
                        disabled>Previous</button>
                    <span id="confirmedPageNumbers"></span>
                    <button class="pagination-btn" id="confirmedNextPage" onclick="changePage('confirmed', 1)">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Appointment Modal -->
    <div class="modal fade emergency-modal" id="rescheduleAppointmentModal" tabindex="-1"
        aria-labelledby="rescheduleAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleAppointmentModalLabel">Reschedule Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rescheduleAppointmentForm" action="{{ route('appointment.reschedule') }}" method="POST">
                        @csrf
                        <input type="hidden" id="rescheduleAppointmentId" name="appointment_id">
                        <div class="mb-3">
                            <label for="rescheduleDate" class="form-label">New Date</label>
                            <input type="date" class="form-control" id="rescheduleDate" name="appointment_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="rescheduleTime" class="form-label">New Time</label>
                            <input type="time" class="form-control" id="rescheduleTime" name="appointment_time"
                                required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm Reschedule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Appointment Modal -->
    <div class="modal fade emergency-modal" id="cancelAppointmentModal" tabindex="-1"
        aria-labelledby="cancelAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelAppointmentModalLabel">Cancel Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this appointment?</p>
                    <form id="cancelAppointmentForm" method="POST" action="{{ route('cancelAppointment') }}">
                        @csrf
                        <input type="hidden" id="cancelAppointmentId" name="appointment_id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Confirm Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>
    <script src="{{ asset('script/staff/all-appointments.js') }}"></script>
@endsection
