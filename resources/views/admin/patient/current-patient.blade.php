@extends('layouts.admin.layout')

@section('title', ' Current Patients - Letty\'s Birthing Home')
@section('page-title', 'Patients Management')

@section('content')
    <div class="container-fluid main-content">
        <div class="patients-card">
            <div class="patients-header">
                <h5>
                    <i class="fas fa-users me-2"></i>Current Patients
                </h5>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputCurrent" placeholder="Search current patients..."
                        oninput="searchPatients('current')">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('branchFilterCurrent')">
                        <span>Branch</span>
                        <div class="d-flex align-items-center">
                            <span id="branchFilterCountCurrent" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="branchFilterCurrent">
                        <div class="filter-option selected" onclick="filterPatients('branch', 'all', 'current')">
                            All Branches</div>
                        @php
                            $branches = $patients->pluck('patient.branch.branch_name')->filter()->unique()->sort();
                        @endphp
                        @foreach ($branches as $branch)
                            <div class="filter-option"
                                onclick="filterPatients('branch', '{{ addslashes($branch) }}', 'current')">
                                {{ $branch }}</div>
                        @endforeach
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
                            <th>Patient Name</th>
                            <th>Address</th>
                            <th>Age</th>
                            <th>Phone Number</th>
                            <th>Branch</th>
                            <th>Consulted By</th>
                            <th>Visit #</th>
                            <th>Next Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            @php
                                $latestVisitInfo = $patient->prenatalVisits
                                    ->flatMap(fn($pv) => $pv->visitInfo)
                                    ->sortByDesc('visit_date')
                                    ->first();

                                $latestPrenatal = $patient->prenatalVisits
                                    ->sortByDesc(fn($pv) => $pv->visitInfo->max('visit_number') ?? 0)
                                    ->first();

                                $consultedBy =
                                    $latestPrenatal && $latestPrenatal->staff
                                        ? trim(
                                            ($latestPrenatal->staff->first_name ?? '') .
                                                ' ' .
                                                ($latestPrenatal->staff->last_name ?? ''),
                                        )
                                        : 'N/A';
                            @endphp

                            <tr data-id="{{ $patient->id }}">
                                <td class="patient-id">{{ $patient->patient->patient_id ?? 'N/A' }}</td>
                                <td class="patient-name">
                                    {{ trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) }}
                                </td>
                                <td class="patient-address">{{ $patient->full_address ?? 'N/A' }}</td>
                                <td class="patient-age">{{ $patient->patient->age ?? 'N/A' }}</td>
                                <td class="patient-phone">{{ $patient->client_phone ?? 'N/A' }}</td>
                                <td class="patient-branch">
                                    {{ $patient->patient && $patient->patient->branch ? $patient->patient->branch->branch_name : 'N/A' }}
                                </td>
                                <td class="patient-consulted">{{ $consultedBy }}</td>
                                <td class="patient-visit">
                                    @if ($latestPrenatal && $latestPrenatal->visitInfo->isNotEmpty())
                                        @php
                                            $num = $latestVisitInfo->visit_number;
                                            $suffix = match ($num) {
                                                1 => 'st',
                                                2 => 'nd',
                                                3 => 'rd',
                                                default => 'th',
                                            };
                                        @endphp
                                        {{ $num . $suffix }} visit
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="patient-date">
                                    {{ $latestVisitInfo && $latestVisitInfo->next_visit_date
                                        ? \Carbon\Carbon::parse($latestVisitInfo->next_visit_date)->format('F d, Y')
                                        : 'N/A' }}
                                </td>
                                <td class="actions-cell">
                                    <a href="{{ route('patientPdfRecords', $patient->id) }}" class="action-btn view-btn"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="10" class="text-center">No patients found.</td>
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

    </main>
    <script src="{{ asset('script/admin/current-patient.js') }}"></script>
@endsection
