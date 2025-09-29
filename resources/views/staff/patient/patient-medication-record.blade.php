@extends('layouts.staff.layout')

@section('title', 'Patient Medication List - Letty\'s Birthing Home')
@section('page-title', 'Patient Medication')

@section('content')

    <div class="container-fluid main-content">
        <div class="medication-card">
            <div class="medication-header">
                <h5>
                    <i class="fas fa-prescription-bottle-alt me-2"></i>Patient Medication Record
                </h5>
                <a href="{{ route('patientMedication') }}" class="add-medication-btn" aria-label="Add new medication">
                    <i class="fas fa-plus me-2"></i>Add Medication
                </a>

            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputMedication" placeholder="Search medication records..."
                        oninput="searchMedications()">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('categoryFilterMedication')">
                        <span>Category</span>
                        <div class="d-flex align-items-center">
                            <span id="categoryFilterCountMedication" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="categoryFilterMedication">
                        <div class="filter-option selected" onclick="filterMedications('category', 'all')">All
                            Categories
                        </div>
                        <div class="filter-option" onclick="filterMedications('category', 'Medication')">
                            Medication</div>
                        <div class="filter-option" onclick="filterMedications('category', 'Supplement')">
                            Supplement</div>
                        <div class="filter-option" onclick="filterMedications('category', 'Other')">Other</div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnMedication" style="display: none;"
                    onclick="clearAllFilters()">
                    Clear All Filters
                </button>
            </div>

            <div class="table-container">
                <table class="appointments-table" id="medicationTable">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                            <th>Issue Date</th>
                            {{-- <th>Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medications as $medication)
                            @foreach ($medication->items as $item)
                                <tr data-id="{{ $medication->id }}">
                                    <td class="patient-name">
                                        {{ $medication->patient->client->first_name }}
                                        {{ $medication->patient->client->last_name }}
                                    </td>
                                    <td class="medication-category">
                                        {{ $item->item->category->name ?? 'N/A' }}
                                    </td>
                                    <td class="item-name">
                                        {{ $item->item->item_name }}
                                    </td>
                                    <td class="quantity">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="remarks">
                                        {{ $medication->notes ?? '—' }}
                                    </td>
                                    <td class="issue-date">
                                        {{ \Carbon\Carbon::parse($medication->prescribed_at)->format('Y-m-d') }}
                                    </td>
                                    {{-- <td class="actions-cell">
                                        <button class="action-btn edit-btn" data-bs-toggle="modal"
                                            data-bs-target="#editMedicationModal{{ $medication->id }}"
                                            title="Edit Medication">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn"
                                            onclick="openDeleteModal('{{ $medication->id }}')" title="Delete Medication">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td> --}}
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No medication records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>


                <div id="noResultsMedication" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No medication records found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="medicationPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="medicationItemsPerPage" onchange="updateItemsPerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="medicationPrevPage" onclick="changePage(-1)"
                        disabled>Previous</button>
                    <span id="medicationPageNumbers"></span>
                    <button class="pagination-btn" id="medicationNextPage" onclick="changePage(1)">Next</button>
                </div>
            </div>
        </div>
    </div>

   
    <!-- Edit Medication Modals -->
    <div class="modal fade" id="editMedicationModal1" tabindex="-1" aria-labelledby="editMedicationModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMedicationModalLabel1">Edit Medication for Jane Doe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMedicationForm1">
                        <div class="mb-3">
                            <label for="editPatientId1" class="form-label">Patient</label>
                            <select class="form-control" id="editPatientId1" name="patient_id" required>
                                <option value="1" selected>Jane Doe</option>
                                <option value="2">John Smith</option>
                                <option value="3">Mary Johnson</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editCategory1" class="form-label">Category</label>
                            <select class="form-control" id="editCategory1" name="category" required>
                                <option value="Medication" selected>Medication</option>
                                <option value="Supplement">Supplement</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editItemName1" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="editItemName1" name="item_name"
                                value="Paracetamol" required>
                        </div>
                        <div class="mb-3">
                            <label for="editQuantity1" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="editQuantity1" name="quantity"
                                min="1" value="10" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRemarks1" class="form-label">Remarks (Optional)</label>
                            <textarea class="form-control" id="editRemarks1" name="remarks" rows="3">Take one tablet every 6 hours</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Medication</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Medication Modal -->
    <div class="modal fade" id="deleteMedicationModal" tabindex="-1" aria-labelledby="deleteMedicationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMedicationModalLabel">Delete Medication Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the medication record <span id="deleteMedicationId"></span>?
                    </p>
                    <form id="deleteMedicationForm">
                        <input type="hidden" id="deleteMedicationCode" name="id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('script/staff/medication-record.js') }}"></script>
@endsection
