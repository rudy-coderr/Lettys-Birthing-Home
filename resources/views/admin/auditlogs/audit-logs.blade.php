@extends('layouts.admin.layout')

@section('title', 'Staff Audit Logs - Letty\'s Birthing Home')
@section('page-title', 'Audit Logs')

@section('content')

        <div class="container-fluid main-content">
            <div class="audit-logs-section">
                <div class="audit-logs-header">
                    <div class="audit-logs-title">
                        <i class="fas fa-file-alt"></i>
                        Staff Audit Logs
                    </div>
                </div>

                <div class="search-filter-section">
                    <div class="search-box">
                        <input type="text" id="searchInputLogs" placeholder="Search audit logs..."
                            oninput="searchLogs()">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleFilter('actionFilterLogs')">
                            <span>Action</span>
                            <div class="d-flex align-items-center">
                                <span id="actionFilterCountLogs" class="filter-count" style="display: none;">0</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </button>
                        <div class="filter-dropdown-menu" id="actionFilterLogs">
                            <div class="filter-option selected" onclick="filterLogs('action', 'all')">All Actions</div>
                            <div class="filter-option" onclick="filterLogs('action', 'login')">Login</div>
                            <div class="filter-option" onclick="filterLogs('action', 'update')">Update</div>
                            <div class="filter-option" onclick="filterLogs('action', 'delete')">Delete</div>
                        </div>
                    </div>
                    <button class="clear-filters-btn" id="clearFiltersBtnLogs" style="display: none;"
                        onclick="clearAllFilters('logs')">
                        Clear All Filters
                    </button>
                </div>

                <div class="audit-logs-table-container">
                    <table class="audit-logs-table" id="auditLogsTable">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Action</th>
                                <th>Date</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditLogs ?? [] as $log)
                                <tr>
                                    <td class="staff-name">{{ $log['staff_name'] }}</td>
                                    <td class="action">{{ $log['action'] }}</td>
                                    <td class="date">{{ $log['date'] }}</td>
                                    <td class="details">{{ $log['details'] }}</td>
                                </tr>
                            @empty
                                <tr class="no-results">
                                    <td colspan="4" class="text-center">No logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div id="noResultsLogs" class="no-results" style="display: none;">
                        <i class="fas fa-search"></i>
                        <p>No logs found matching your search criteria.</p>
                    </div>
                </div>

                <div class="pagination-container" id="logsPagination">
                    <div class="items-per-page">
                        <span>Items per page:</span>
                        <select id="logsItemsPerPage" onchange="updateItemsPerPage('logs')">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    <div class="pagination-controls">
                        <button class="pagination-btn" id="logsPrevPage" onclick="changePage('logs', -1)"
                            disabled>Previous</button>
                        <span id="logsPageNumbers"></span>
                        <button class="pagination-btn" id="logsNextPage" onclick="changePage('logs', 1)">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
  <script src="{{ asset('script/admin/audit-logs.js') }}"></script>
@endsection
