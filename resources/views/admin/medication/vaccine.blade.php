@extends('layouts.admin.layout')

@section('title', 'Vaccine - Letty\'s Birthing Home')
@section('page-title', 'Inventory Management')

@section('content')

    <div class="container-fluid main-content">
        <div class="medication-card">
            <div class="medication-header">
                <h5>
                    <i class="fas fa-syringe me-2"></i>Vaccines Inventory
                </h5>

                <div class="header-actions d-flex gap-2">
                    <a href="{{ route('vaccines.create') }}" class="add-medication-btn" aria-label="Add new item">
                        <i class="fas fa-plus me-2"></i>Add Vaccine
                    </a>

                    <a href="#" class="add-medication-btn" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                        <i class="fas fa-plus me-2"></i>Add Unit
                    </a>

                </div>

            </div>

            {{-- Search + Filter --}}
            <div class="search-filter-section">
                <div class="search-box">
                    <input type="text" id="searchInputVaccines" placeholder="Search vaccines..." oninput="searchItems()">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter('filterDropdownVaccinesStatus')">
                        <span>Status</span>
                        <div class="d-flex align-items-center">
                            <span id="filterCountVaccinesStatus" class="filter-count" style="display: none;">0</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="filter-dropdown-menu" id="filterDropdownVaccinesStatus">
                        <div class="filter-option selected" onclick="filterItems('status', 'all')">All Status</div>
                        <div class="filter-option" onclick="filterItems('status', 'available')">Available</div>
                        <div class="filter-option" onclick="filterItems('status', 'low stock')">Low Stock</div>
                        <div class="filter-option" onclick="filterItems('status', 'out')">Out of Stock</div>
                    </div>
                </div>
                <button class="clear-filters-btn" id="clearFiltersBtnVaccines" style="display: none;"
                    onclick="clearAllFilters()">
                    Clear All Filters
                </button>
            </div>

            {{-- Table --}}
            <div class="table-container">
                <table class="appointments-table" id="vaccinesTable">
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
                        @forelse ($vaccines as $vaccine)
                            <tr data-status="{{ $vaccine->stock_status ?? 'N/A' }}" data-id="{{ $vaccine->id }}">

                                {{-- Item Name --}}
                                <td class="item-name">{{ $vaccine->item_name ?? 'N/A' }}</td>

                                {{-- Batch No. --}}
                                <td>{{ $vaccine->batch_no ?? 'N/A' }}</td>

                                {{-- Quantity --}}
                                <td class="quantity-info">{{ $vaccine->quantity ?? 0 }}</td>

                                {{-- Unit --}}
                                <td>{{ $vaccine->unit->name ?? 'N/A' }}</td>

                                {{-- Expiry Date --}}
                                <td>
                                    {{ $vaccine->expiry_date ? \Carbon\Carbon::parse($vaccine->expiry_date)->format('M d, Y') : 'N/A' }}
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="status-badge status-{{ strtolower($vaccine->stock_status) }}">
                                        {{ $vaccine->stock_status }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="actions-cell">
                                    <a href="{{ route('vaccines.show', $vaccine->id) }}" class="action-btn view-btn"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    <button class="action-btn restock-btn" title="Restock"
                                        onclick="openRestockModal(
                                            '{{ $vaccine->id }}',
                                            '{{ $vaccine->item_name ?? 'Item' }}',
                                            '{{ $vaccine->quantity ?? 0 }} {{ $vaccine->unit->name ?? 'N/A' }}',
                                            '{{ route('vaccines.restock', $vaccine->id) }}'
                                        )">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button class="action-btn delete-btn" title="Delete"
                                        onclick="openDeleteModal('{{ route('vaccines.destroy', $vaccine->id) }}', '{{ $vaccine->item_name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr class="no-results">
                                <td colspan="7" class="text-center">
                                    <i class="fas fa-box-open"></i>
                                    <p>No vaccines available in the inventory.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- No search results message --}}
                <div id="noResultsVaccines" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>No vaccines found matching your search criteria.</p>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="pagination-container" id="vaccinesPagination">
                <div class="items-per-page">
                    <span>Items per page:</span>
                    <select id="vaccinesItemsPerPage" onchange="updateItemsPerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="vaccinesPrevPage" onclick="changePage(-1)" disabled>Previous</button>
                    <span id="vaccinesPageNumbers"></span>
                    <button class="pagination-btn" id="vaccinesNextPage" onclick="changePage(1)">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Restock Modal -->
    <div class="modal fade restock-modal" id="restockModal" tabindex="-1" aria-labelledby="restockModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restockModalLabel">Restock Vaccine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Restocking <span id="restockItemName"></span></p>
                    <p>Current Quantity: <span id="restockCurrentQuantity"></span></p>
                    <form id="restockForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="restockItemId" name="item_id">
                        <input type="hidden" id="restockItemType" name="item_type" value="vaccine">
                        <div class="mb-3">
                            <label for="restockQuantity" class="form-label">Add Quantity</label>
                            <input type="number" class="form-control" id="restockQuantity" name="quantity"
                                min="0" required>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Vaccine</h5>
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


    {{-- Emergency Modal Placeholder --}}
    <div id="emergency-container">
        @include('partials.emergencyModal')
    </div>
    </main>

    <script src="{{ asset('script/admin/vaccine.js') }}"></script>
@endsection
