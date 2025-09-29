@extends('layouts.admin.layout')

@section('title', ' All Patients Records - Letty\'s Birthing Home')
@section('page-title', 'Patients Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="patients-card">
            <div class="patients-header">
                <h5>
                    <i class="fas fa-file-medical me-2"></i>Patient Records
                </h5>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputRecords" placeholder="Search patient records..."
                        oninput="searchPatients('records')">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('ageFilterRecords')">
                        <span>Age</span>
                        <div class="d-flex align-items-center">
                            <span id="ageFilterCountRecords" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="ageFilterRecords">
                        <div class="filter-option selected" onclick="filterPatients('age', 'all', 'records')">All
                            Ages</div>
                        <div class="filter-option" onclick="filterPatients('age', 'young', 'records')">18-25 years
                        </div>
                        <div class="filter-option" onclick="filterPatients('age', 'adult', 'records')">26-35 years
                        </div>
                        <div class="filter-option" onclick="filterPatients('age', 'mature', 'records')">36+ years
                        </div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnRecords" style="display: none;"
                    onclick="clearAllFilters('records')">
                    Clear All Filters
                </button>
            </div>

            <div class="table-container">
                <table class="appointments-table" id="recordsPatientsTable">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr data-id="{{ $patient->patient?->patient_id }}">
                                <td class="patient-id">{{ $patient->patient?->patient_id ?? 'N/A' }}</td>
                                <td class="patient-name">{{ $patient->first_name }} {{ $patient->last_name }}
                                </td>
                                <td class="patient-address">
                                    {{ $patient->address?->village ? $patient->address->village . ', ' : '' }}
                                    {{ $patient->address?->city_municipality ? $patient->address->city_municipality . ', ' : '' }}
                                    {{ $patient->address?->province ?? 'N/A' }}
                                </td>
                                <td class="patient-contact">{{ $patient->client_phone }}</td>
                                <td class="patient-age">{{ $patient->patient?->age ?? 'N/A' }}</td>
                                <td class="actions-cell">
                                    <a href="{{ route('patientPdfRecords', $patient->id) }}" class="action-btn view-btn"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="6" class="text-center">No patients with prenatal records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="noResultsRecords" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No patient records found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="recordsPatientsPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="recordsItemsPerPage" onchange="updateItemsPerPage('records')">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="recordsPrevPage" onclick="changePage('records', -1)"
                        disabled>Previous</button>
                    <span id="recordsPageNumbers"></span>
                    <button class="pagination-btn" id="recordsNextPage" onclick="changePage('records', 1)">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>

    <script src="{{ asset('script/admin/all-patient.js') }}"></script>
@endsection
