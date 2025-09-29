@extends('layouts.admin.layout')

@section('title', 'Medicine - Letty\'s Birthing Home')
@section('page-title', 'Inventory Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="medication-card">
            <div class="medication-header">
                <h5>
                    <i class="fas fa-pills me-2"></i>Medicines Inventory
                </h5>
                <div class="header-actions d-flex gap-2">
                    <!-- Add Medicine Button -->
                    <a href="{{ route('medicines.create') }}" class=" add-medication-btn">
                        <i class="fas fa-plus me-2"></i>Add Medicine
                    </a>

                    <a href="#" class="add-medication-btn" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                        <i class="fas fa-plus me-2"></i>Add Unit
                    </a>

                </div>
            </div>

            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputMedicines" placeholder="Search medicines..."
                        oninput="searchItems()">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button type="button" class="filter-btn" onclick="toggleFilter('filterDropdownMedicinesStatus')">
                        <span>Status</span>
                        <div class="d-flex align-items-center">
                            <span id="filterCountMedicinesStatus" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="filterDropdownMedicinesStatus">
                        <div class="filter-option selected" onclick="filterItems('status', 'all')">All Status</div>
                        <div class="filter-option" onclick="filterItems('status', 'available')">Available</div>
                        <div class="filter-option" onclick="filterItems('status', 'low stock')">Low Stock</div>
                        <div class="filter-option" onclick="filterItems('status', 'out')">Out of Stock</div>
                    </div>
                </div>

                <button class="clear-filters-btn" id="clearFiltersBtnMedicines" style="display: none;"
                    onclick="clearAllFilters()">
                    Clear All Filters
                </button>
            </div>

            <div class="table-container">
                <table class="appointments-table" id="medicinesTable">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Batch No.</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicines as $medicine)
                            <tr data-category="{{ $medicine->category->name ?? 'N/A' }}"
                                data-status="{{ $medicine->stock_status ?? 'N/A' }}" data-id="{{ $medicine->id }}">

                                {{-- Item Name --}}
                                <td class="item-name">{{ $medicine->item_name ?? 'N/A' }}</td>

                               

                                {{-- Batch No. --}}
                                <td>{{ $medicine->batch_no ?? 'N/A' }}</td>

                                {{-- Quantity --}}
                                <td class="quantity-info">{{ $medicine->quantity ?? 0 }}</td>

                                {{-- Unit --}}
                                <td>{{ $medicine->unit->name ?? 'N/A' }}</td>

                                {{-- Expiry Date --}}
                                <td>
                                    {{ $medicine->expiry_date ? \Carbon\Carbon::parse($medicine->expiry_date)->format('M d, Y') : 'N/A' }}
                                </td>

                                {{-- Status (from accessor) --}}
                                <td>
                                    <span class="status-badge status-{{ strtolower($medicine->stock_status) }}">
                                        {{ $medicine->stock_status }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="actions-cell">
                                    <a href="{{ route('admin.medicines.show', $medicine->id) }}"
                                        class="action-btn view-btn" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    <button class="action-btn restock-btn" title="Restock"
                                        onclick="openRestockModal(
                                        '{{ $medicine->id }}',
                                        '{{ $medicine->item_name ?? 'Item' }}',
                                        '{{ $medicine->quantity ?? 0 }} {{ $medicine->unit->name ?? 'N/A' }}',
                                        '{{ route('medicines.restock', $medicine->id) }}'
                                    )">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Delete"
                                        onclick="openDeleteModal('{{ route('medicines.destroy', $medicine->id) }}', '{{ $medicine->item_name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="8" class="text-center">
                                    <i class="fas fa-box-open"></i>
                                    <p>No medicines available in the inventory.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- No search results message --}}
                <div id="noResultsMedicines" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No medicines found matching your search criteria.</p>
                </div>
            </div>

            <div class="pagination-container" id="medicinesPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="medicinesItemsPerPage" onchange="updateItemsPerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="medicinesPrevPage" onclick="changePage(-1)"
                        disabled>Previous</button>
                    <span id="medicinesPageNumbers"></span>
                    <button class="pagination-btn" id="medicinesNextPage" onclick="changePage(1)">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="closeImageModal()">×</span>
        <div class="image-modal-content">
            <img id="modalImage" src="" alt="">
        </div>
    </div>


    <!-- Restock Modal -->
    <div class="modal fade restock-modal" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restockModalLabel">Restock Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Restocking <span id="restockItemName"></span></p>
                    <p>Current Quantity: <span id="restockCurrentQuantity"></span></p>
                    <form id="restockForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="restockItemId" name="item_id">
                        <input type="hidden" id="restockItemType" name="item_type" value="medicine">

                        <div class="mb-3">
                            <label for="restockQuantity" class="form-label">Add Quantity</label>
                            <input type="number" class="form-control" id="restockQuantity" name="quantity"
                                min="1" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Restock</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade delete-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <span id="deleteItemName" class="fw-bold"></span>?</p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Add Unit Modal -->
<div class="modal fade add-unit-modal" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addUnitModalLabel">Add New Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p>Please enter the new unit name:</p>
                <form id="addUnitForm" method="POST" action="{{ route('units.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="unitName" class="form-label">Unit Name</label>
                        <input type="text" class="form-control" id="unitName" name="name"
                             placeholder="ex: pcs, bottle, ml" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- Emergency Modal Placeholder -->
    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>
    <script src="{{ asset('script/admin/medicine.js') }}"></script>
@endsection
