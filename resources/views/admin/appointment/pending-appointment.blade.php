@extends('layouts.admin.layout')

@section('title', 'All Appointments - Letty\'s Birthing Home')
@section('page-title', 'Appointment Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="appointments-card">
            <div class="appointments-header">
                <h5>
                    <i class="fas fa-hourglass-half me-2"></i>All Appointments
                </h5>
                <a href="{{ route('admin.addAppointment') }}" class="add-appointment-btn" aria-label="Add new appointment">
                    <i class="fas fa-plus me-2"></i>Add Appointment
                </a>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputAppointments" placeholder="Search appointments..."
                        oninput="searchAppointments()">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('filterDropdownStatus')">
                        <span>Status</span>
                        <div class="d-flex align-items-center">
                            <span id="filterCountStatus" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="filterDropdownStatus">
                        <div class="filter-option selected" onclick="filterAppointments('status', 'all')">All Statuses</div>
                        <div class="filter-option" onclick="filterAppointments('status', 'pending')">Pending</div>
                        <div class="filter-option" onclick="filterAppointments('status', 'no-show')">No-show</div>
                        <div class="filter-option" onclick="filterAppointments('status', 'cancelled')">Cancelled</div>
                    </div>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('filterDropdownBranch')">
                        <span>Branch</span>
                        <div class="d-flex align-items-center">
                            <span id="filterCountBranch" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="filterDropdownBranch">
                        <div class="filter-option selected" onclick="filterAppointments('branch', 'all')">All Branches</div>
                        <div class="filter-option" onclick="filterAppointments('branch', 'sta. justina')">Sta. Justina</div>
                        <div class="filter-option" onclick="filterAppointments('branch', 'san pedro')">San Pedro</div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnAppointments" style="display: none;"
                    onclick="clearAllFilters()">
                    Clear All Filters
                </button>
            </div>
            <div class="table-container">
                <table class="appointments-table" id="appointmentsTable">
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
                                data-status="{{ strtolower($appointment->appointment_status->status_name ?? 'unknown') }}"
                                data-branch="{{ strtolower($appointment->branch->branch_name ?? 'N/A') }}">
                                <td class="appointment-first-name">{{ $appointment->client->first_name ?? 'N/A' }}</td>
                                <td class="appointment-last-name">{{ $appointment->client->last_name ?? 'N/A' }}</td>
                                <td class="appointment-phone">{{ $appointment->client->client_phone ?? 'N/A' }}</td>
                                <td class="appointment-branch">{{ $appointment->branch->branch_name ?? 'N/A' }}</td>
                                <td class="appointment-reason">{{ $appointment->appointment_reason ?? 'N/A' }}</td>
                                <td class="appointment-schedule">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('F d, Y, g:ia') }}
                                </td>
                                <td
                                    class="appointment-status status-{{ strtolower($appointment->appointment_status->status_name ?? 'unknown') }}">
                                    {{ $appointment->appointment_status->status_name ?? 'N/A' }}
                                </td>
                                <td class="actions-cell">
                                    <!-- Edit button always -->
                                    <a href="{{ route('admin.editAppointment', $appointment->id) }}"
                                        class="action-btn edit-btn" title="Edit Appointment">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Reschedule button always -->
                                    <button class="action-btn resched-btn" title="Reschedule"
                                        data-id="{{ $appointment->id }}" data-date="{{ $appointment->appointment_date }}"
                                        data-time="{{ $appointment->appointment_time }}"
                                        onclick="openRescheduleModal(this)">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>

                                    <!-- Cancel button always -->
                                    <button class="action-btn cancel-btn" title="Cancel Appointment"
                                        data-id="{{ $appointment->id }}"
                                        onclick="openCancelModal('{{ $appointment->id }}')">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    <!-- Delete button only for No-show -->
                                    @if (($appointment->appointment_status->status_name ?? '') === 'No-show')
                                        <button class="action-btn delete-btn" title="Delete Appointment"
                                            data-id="{{ $appointment->id }}"
                                            onclick="openDeleteModal('{{ $appointment->id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>


                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="8" class="text-center">
                                    <i class="fas fa-calendar-times"></i>
                                    <p>No pending, no-show, or cancelled appointments found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="noResultsAppointments" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No appointments found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="appointmentsPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="appointmentsItemsPerPage" onchange="updateItemsPerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="appointmentsPrevPage" onclick="changePage(-1)"
                        disabled>Previous</button>
                    <span id="appointmentsPageNumbers"></span>
                    <button class="pagination-btn" id="appointmentsNextPage" onclick="changePage(1)">Next</button>
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

    <!-- Delete Appointment Modal -->
    <div class="modal fade emergency-modal" id="deleteAppointmentModal" tabindex="-1"
        aria-labelledby="deleteAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAppointmentModalLabel">Delete Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this appointment? This action cannot be undone.</p>
                    <form id="deleteAppointmentForm" method="POST" action="{{ route('admin.appointment.delete') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="deleteAppointmentId" name="appointment_id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Delete</button>
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
    <script src="{{ asset('script/admin/all-appointment.js') }}"></script>
@endsection
