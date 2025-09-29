@extends('layouts.admin.layout')

@section('title', 'Today\'s Appointment Scheduled - Letty\'s Birthing Home')
@section('page-title', 'Appointment Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="appointments-card">
            <div class="appointments-header">
                <h5>
                    <i class="fas fa-calendar-check me-2"></i>Today's Schedule
                </h5>
                <a href="{{ route('admin.addAppointment') }}" class="add-appointment-btn" aria-label="Add new appointment">
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
                        <div class="filter-option selected" onclick="filterAppointments('reason', 'all', 'all')">
                            All Reasons</div>
                        <div class="filter-option" onclick="filterAppointments('reason', 'checkup', 'all')">
                            Checkup</div>
                        <div class="filter-option" onclick="filterAppointments('reason', 'consultation', 'all')">
                            Consultation</div>
                        <div class="filter-option" onclick="filterAppointments('reason', 'follow-up', 'all')">
                            Follow-Up</div>
                    </div>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('branchFilterAll')">
                        <span>Branch</span>
                        <div class="d-flex align-items-center">
                            <span id="branchFilterCountAll" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="branchFilterAll">
                        <div class="filter-option selected" onclick="filterAppointments('branch', 'all', 'all')">
                            All Branches</div>
                        <div class="filter-option" onclick="filterAppointments('branch', 'sta. justina', 'all')">
                            Sta. Justina</div>
                        <div class="filter-option" onclick="filterAppointments('branch', 'san pedro', 'all')">
                            San Pedro</div>
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
                            <th>Branch</th>
                            <th>Reason</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr data-id="AP{{ $appointment->id }}"
                                data-branch="{{ strtolower($appointment->branch->branch_name ?? 'N/A') }}">
                                <td class="patient-first-name">{{ $appointment->client->first_name ?? 'N/A' }}</td>
                                <td class="patient-last-name">{{ $appointment->client->last_name ?? 'N/A' }}</td>
                                <td class="patient-phone">{{ $appointment->client->client_phone ?? 'N/A' }}</td>
                                <td class="appointment-branch">{{ $appointment->branch->branch_name ?? 'N/A' }}</td>
                                <td class="appointment-reason">{{ $appointment->appointment_reason ?? 'N/A' }}</td>
                                <td class="schedule-info">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('F d, Y, g:ia') }}
                                </td>
                                <td
                                    class="appointment-status status-{{ strtolower($appointment->appointment_status->status_name ?? 'unknown') }}">
                                    {{ $appointment->appointment_status->status_name ?? 'N/A' }}
                                </td>
                                <td class="actions-cell">
                                    <!-- Reschedule Button (always visible) -->
                                    <button class="action-btn resched-btn" title="Reschedule"
                                        data-id="{{ $appointment->id }}" data-date="{{ $appointment->appointment_date }}"
                                        data-time="{{ $appointment->appointment_time }}"
                                        onclick="openRescheduleModal(this)">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                    <!-- Cancel Button (always visible) -->
                                    <button class="action-btn cancel-btn" title="Cancel Appointment"
                                        data-id="{{ $appointment->id }}"
                                        onclick="openCancelModal('{{ $appointment->id }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="8" class="text-center">No appointments available.</td>
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
                    <form id="rescheduleAppointmentForm" action="{{ route('admin.appointment.reschedule') }}"
                        method="POST">
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
                    <form id="cancelAppointmentForm" method="POST" action="{{ route('admin.cancelAppointment') }}">
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

    <script src="{{ asset('script/admin/todays-scheduled.js') }}"></script>
@endsection
