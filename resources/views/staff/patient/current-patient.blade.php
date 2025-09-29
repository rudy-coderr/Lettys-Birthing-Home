@extends('layouts.staff.layout')

@section('title', 'Current Patients - Letty\'s Birthing Home')
@section('page-title', 'Patients Management')

@section('content')
    <!-- Main Content -->
    <div class="container-fluid main-content">
        <div class="patients-card">
            <div class="patients-header">
                <h5>
                    <i class="fas fa-users me-2"></i>Current Patients
                </h5>
                <a href="{{ route('addPatient') }}" class="add-patient-btn" aria-label="Add new patient">
                    <i class="fas fa-plus me-2"></i>Add Patient
                </a>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputCurrent" placeholder="Search current patients..."
                        oninput="searchPatients('current')">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('ageFilterCurrent')">
                        <span>Age</span>
                        <div class="d-flex align-items-center">
                            <span id="ageFilterCountCurrent" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="ageFilterCurrent">
                        <div class="filter-option selected" onclick="filterPatients('age', 'all', 'current')">All
                            Ages</div>
                        <div class="filter-option" onclick="filterPatients('age', 'young', 'current')">18-25 years
                        </div>
                        <div class="filter-option" onclick="filterPatients('age', 'adult', 'current')">26-35 years
                        </div>
                        <div class="filter-option" onclick="filterPatients('age', 'mature', 'current')">36+ years
                        </div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnCurrent" style="display: none;"
                    onclick="clearAllFilters('current')">
                    Clear All Filters
                </button>
            </div>

            <div class="table-container">
                <table class="appointments-table" id="currentPatientsTable">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Age</th>
                            <th>Phone Number</th>
                            <th>Visit #</th>
                            <th>Next Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            @php
                                // 🔹 Latest prenatal visit by ID (the newest one)
                                $latestPrenatal = $patient->prenatalVisits->sortByDesc('id')->first();

                                // 🔹 Latest visitInfo (highest visit_number) from that prenatal visit
                                $latestVisitInfo = $latestPrenatal?->visitInfo->sortByDesc('visit_number')->first();

                                // 🔹 Determine visit display
                                if ($latestPrenatal && $latestPrenatal->prenatal_status_id != 2 && $latestVisitInfo) {
                                    $num = $latestVisitInfo->visit_number;
                                    $suffix = match ($num) {
                                        1 => 'st',
                                        2 => 'nd',
                                        3 => 'rd',
                                        default => 'th',
                                    };
                                    $visitText = $num . $suffix . ' visit';
                                } elseif ($latestPrenatal && $latestPrenatal->prenatal_status_id == 2) {
                                    $visitText = 'Completed';
                                } else {
                                    $visitText = 'N/A';
                                }

                                // 🔹 Next visit date
                                $nextVisitDate = $latestVisitInfo?->next_visit_date
                                    ? \Carbon\Carbon::parse($latestVisitInfo->next_visit_date)->format('F d, Y')
                                    : 'N/A';
                            @endphp

                            <tr data-id="{{ $patient->id }}">
                                <td class="patient-id">{{ $patient->patient->patient_id ?? 'N/A' }}</td>
                                <td class="patient-name">{{ $patient->first_name }}</td>
                                <td class="patient-name">{{ $patient->last_name }}</td>
                                <td class="patient-address">{{ $patient->full_address }}</td>
                                <td class="patient-age">{{ $patient->patient->age ?? 'N/A' }}</td>
                                <td class="patient-phone">{{ $patient->client_phone }}</td>
                                <td class="patient-visit">{{ $visitText }}</td>
                                <td class="patient-date">{{ $nextVisitDate }}</td>
                                <td class="actions-cell">
                                    {{-- Edit record --}}
                                    <a href="{{ route('patient.editLatestVisit', $patient->id) }}"
                                        class="action-btn edit-btn" title="Edit Record">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Add Record --}}
                                    <a href="{{ route('addRecords', $patient->id) }}" class="action-btn add-record-btn"
                                        title="Add Prenatal Record">
                                         <i class="fas fa-stethoscope"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="9" class="text-center">No patients found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                <div id="noResultsCurrent" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No patients found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="currentPatientsPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="currentItemsPerPage" onchange="updateItemsPerPage('current')">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="currentPrevPage" onclick="changePage('current', -1)"
                        disabled>Previous</button>
                    <span id="currentPageNumbers"></span>
                    <button class="pagination-btn" id="currentNextPage" onclick="changePage('current', 1)">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>
    <script src="{{ asset('script/staff/current-patient.js') }}"></script>
@endsection
