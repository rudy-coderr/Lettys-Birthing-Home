@extends('layouts.staff.layout')

@section('title', 'Patients Pdf Records - Letty\'s Birthing Home')
@section('page-title', 'Patients Pdf Records')

@section('content')
    <div class="container-fluid main-content">
        <div class="visits-card">
            <div class="visits-header">
                <h5><i class="fas fa-file-medical me-2"></i>Patient Visit Records</h5>
            </div>

            <div class="search-filter-section">
                <!-- Search Box -->
                <div class="search-box">
                    <input type="text" id="searchInputVisits" placeholder="Search visit records..." oninput="applyFilters()">
                    <i class="fas fa-search search-icon"></i>
                </div>

                <!-- Record Type Dropdown -->
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('recordTypeFilter')">
                        <span id="selectedRecordType">Record For</span>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="recordTypeFilter">
                        <div class="filter-option selected" onclick="setRecordType('all')">All Records</div>
                        <div class="filter-option" onclick="setRecordType('prenatal')">Prenatal Visit</div>
                        <div class="filter-option" onclick="setRecordType('registration')">Baby Registration</div>
                        <div class="filter-option" onclick="setRecordType('intrapartum')">Intrapartum</div>
                        <div class="filter-option" onclick="setRecordType('postpartum')">Postpartum</div>
                    </div>
                </div>

                <!-- Date Filter -->
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('dateFilterVisits')">
                        <span>Visit Date</span>
                        <div class="d-flex align-items-center">
                            <span id="dateFilterCountVisits" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="dateFilterVisits">
                        <div class="filter-option selected" onclick="setDateFilter('all')">All Dates</div>
                        <div class="filter-option" onclick="setDateFilter('2025')">2025</div>
                        <div class="filter-option" onclick="setDateFilter('2024')">2024</div>
                        <div class="filter-option" onclick="setDateFilter('2023')">2023</div>
                    </div>
                </div>

                <button class="clear-filters-btn" id="clearFiltersBtnVisits" style="display: none;"
                    onclick="clearAllFilters()">
                    Clear Filters
                </button>
            </div>

            <div class="table-container">
                <table class="visits-table" id="visitsTable">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>PDF File Name</th>
                            <th>Visit Date / Baby DOB</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $patientId = $patient->patient->patient_id ?? 'N/A';
                            $patientName = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));
                        @endphp

                        @forelse($allPatientPdfRecords as $record)
                            @php
                                if ($record->intrapartumRecord) {
                                    $type = 'intrapartum';
                                    $date = $record->intrapartumRecord->created_at ?? null;
                                } elseif ($record->postpartumRecord) {
                                    $type = 'postpartum';
                                    $date = $record->postpartumRecord->created_at ?? null;
                                } elseif ($record->babyRegistration) {
                                    $type = 'registration';
                                    $date = $record->babyRegistration->date_of_birth ?? null;
                                } elseif ($record->visit) {
                                    $type = 'prenatal';
                                    $date = $record->visit->visitInfo->first()->visit_date ?? null;
                                } else {
                                    $type = 'other';
                                    $date = null;
                                }

                                $displayDate = $date ? \Carbon\Carbon::parse($date)->format('M d, Y') : 'N/A';
                                $year = $date ? \Carbon\Carbon::parse($date)->format('Y') : '';
                            @endphp

                            <tr data-type="{{ $type }}" data-visit-date="{{ $year }}">
                                <td>{{ $patientId }}</td>
                                <td>{{ $patientName }}</td>
                                <td>{{ $record->file_name }}</td>
                                <td>{{ $displayDate }}</td>
                                <td>{{ ucfirst($type) }}</td>
                                <td>
                                    {{-- Download --}}
                                    <a href="{{ route('downloadRecord', ['patient' => $patient->id, 'record' => $record->id]) }}"
                                        class="action-btn download-btn">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    {{-- View --}}
                                    <a href="{{ route('pdfRecord', ['record' => $record->id]) }}"
                                        class="action-btn view-btn" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Edit (Dynamic based on type) --}}
                                    @if ($type === 'intrapartum')
                                        <a href="{{ route('editIntrapartum', ['record' => $record->id]) }}"
                                            class="action-btn edit-btn" title="Edit Intrapartum">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @elseif($type === 'postpartum')
                                        <a href="{{ route('editPostpartum', ['record' => $record->id]) }}"
                                            class="action-btn edit-btn" title="Edit Postpartum">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @elseif($type === 'prenatal')
                                        <a href="{{ route('editPrenatal', ['record' => $record->id]) }}"
                                            class="action-btn edit-btn" title="Edit Prenatal">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @elseif($type === 'registration')
                                        <a href="{{ route('editRegistration', ['record' => $record->id]) }}"
                                            class="action-btn edit-btn" title="Edit Registration">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No PDF records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div id="noResultsVisits" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No records found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="visitsPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="visitsItemsPerPage" onchange="updateItemsPerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="visitsPrevPage" onclick="changePage(-1)" disabled>Previous</button>
                    <span id="visitsPageNumbers"></span>
                    <button class="pagination-btn" id="visitsNextPage" onclick="changePage(1)">Next</button>
                </div>
            </div>
        </div>

        <div id="emergency-container">
            @include('partials.emergencyModal')
        </div>
    </div>

    <script src="{{ asset('script/staff/patient-record.js') }}"></script>
@endsection
