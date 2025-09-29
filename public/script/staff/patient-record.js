document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    // Sidebar toggle
    btnOpen.addEventListener("click", function() {
        sidebar.classList.add("mobile-show");
        sidebarOverlay.classList.add("show");
    });

    btnClose.addEventListener("click", function() {
        sidebar.classList.remove("mobile-show");
        sidebarOverlay.classList.remove("show");
    });

    sidebarOverlay.addEventListener("click", function() {
        sidebar.classList.remove("mobile-show");
        this.classList.remove("show");
    });

    // Dropdown toggle
    window.toggleDropdown = function(element) {
        const icon = element.querySelector('.dropdown-icon');
        icon.classList.toggle('rotate');
    };

    // Filter dropdown toggle
    window.toggleFilter = function(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('show');
    };

    // Close filter dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const filterDropdowns = document.querySelectorAll('.filter-dropdown-menu');
        const filterButtons = document.querySelectorAll('.filter-btn');
        if (!Array.from(filterButtons).some(btn => btn.contains(event.target)) &&
            !Array.from(filterDropdowns).some(dropdown => dropdown.contains(event.target))) {
            filterDropdowns.forEach(dropdown => dropdown.classList.remove('show'));
        }
    });

    // Search and filter functionality
    const state = {
        visits: {
            currentPage: 1,
            itemsPerPage: 10,
            dateFilter: 'all',
            recordType: 'all',
            searchQuery: ''
        }
    };

    function getVisibleRows() {
        const table = document.getElementById('visitsTable');
        const rows = Array.from(table.querySelectorAll('tbody tr:not(.no-results)'));
        const { dateFilter, recordType, searchQuery } = state.visits;

        return rows.filter(row => {
            const patientId = row.querySelector('.patient-id')?.textContent.toLowerCase() || '';
            const patientName = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
            const pdfFile = row.querySelector('.pdf-file')?.textContent.toLowerCase() || '';
            const visitDate = row.querySelector('.visit-date')?.textContent.toLowerCase() || '';
            const visitYear = row.dataset.visitDate?.toLowerCase() || '';
            const rowType = row.dataset.type?.toLowerCase() || '';

            const matchesSearch = searchQuery === '' ||
                patientId.includes(searchQuery) ||
                patientName.includes(searchQuery) ||
                pdfFile.includes(searchQuery) ||
                visitDate.includes(searchQuery);

            const matchesDate = dateFilter === 'all' || visitYear === dateFilter;
            const matchesType = recordType === 'all' || recordType === rowType;

            return matchesSearch && matchesDate && matchesType;
        });
    }

    function updateTable() {
        const table = document.getElementById('visitsTable');
        const rows = Array.from(table.querySelectorAll('tbody tr:not(.no-results)'));
        const visibleRows = getVisibleRows();
        const { currentPage, itemsPerPage } = state.visits;
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);

        // Update filter count
        const dateFilterCount = document.getElementById('dateFilterCountVisits');
        dateFilterCount.textContent = state.visits.dateFilter !== 'all' ? totalRows : '0';
        dateFilterCount.style.display = state.visits.dateFilter !== 'all' ? 'inline' : 'none';

        document.getElementById('clearFiltersBtnVisits').style.display =
            state.visits.dateFilter !== 'all' || state.visits.recordType !== 'all' || state.visits.searchQuery
                ? 'block'
                : 'none';

        // Update pagination
        state.visits.currentPage = Math.min(currentPage, Math.max(1, totalPages));
        const start = (state.visits.currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Show/hide rows
        rows.forEach(row => row.style.display = 'none');
        visibleRows.slice(start, end).forEach(row => row.style.display = '');

        // Update no results message
        const noResults = document.getElementById('noResultsVisits');
        const hasVisits = rows.length > 0;
        const isFiltered = state.visits.searchQuery !== '' || state.visits.dateFilter !== 'all' || state.visits.recordType !== 'all';
        noResults.style.display = hasVisits && totalRows === 0 && isFiltered ? 'block' : 'none';

        // Update pagination controls
        const prevBtn = document.getElementById('visitsPrevPage');
        const nextBtn = document.getElementById('visitsNextPage');
        prevBtn.disabled = state.visits.currentPage === 1;
        nextBtn.disabled = state.visits.currentPage === totalPages || totalPages === 0;

        const pageNumbers = document.getElementById('visitsPageNumbers');
        pageNumbers.textContent = totalPages > 0 ? `Page ${state.visits.currentPage} of ${totalPages}` : 'Page 0 of 0';
    }

    // 🔹 Search
    window.searchVisits = function() {
        state.visits.searchQuery = document.getElementById('searchInputVisits').value.toLowerCase();
        state.visits.currentPage = 1;
        updateTable();
    };

    // 🔹 Date filter
    window.filterVisits = function(category, value) {
        const dropdown = document.getElementById('dateFilterVisits');
        const options = dropdown.querySelectorAll('.filter-option');
        options.forEach(opt => opt.classList.remove('selected'));
        event.target.classList.add('selected');
        dropdown.classList.remove('show');

        state.visits.dateFilter = value;
        state.visits.currentPage = 1;
        updateTable();
    };

    // 🔹 Record type filter
    window.setRecordType = function(type) {
        const dropdown = document.getElementById('recordTypeFilter');
        const options = dropdown.querySelectorAll('.filter-option');
        options.forEach(opt => opt.classList.remove('selected'));
        event.target.classList.add('selected');
        dropdown.classList.remove('show');

        state.visits.recordType = type;
        state.visits.currentPage = 1;

        const label = document.getElementById('selectedRecordType');
        label.textContent = type === 'all' ? 'Record For' : type === 'prenatal' ? 'Prenatal Visit' : 'Baby Registration';

        updateTable();
    };

    // 🔹 Clear all
    window.clearAllFilters = function() {
        state.visits.searchQuery = '';
        state.visits.dateFilter = 'all';
        state.visits.recordType = 'all';

        document.getElementById('searchInputVisits').value = '';
        document.getElementById('selectedRecordType').textContent = 'Record For';

        // reset date
        document.querySelectorAll('#dateFilterVisits .filter-option').forEach(opt => opt.classList.remove('selected'));
        document.querySelector('#dateFilterVisits .filter-option:first-child').classList.add('selected');

        // reset record type
        document.querySelectorAll('#recordTypeFilter .filter-option').forEach(opt => opt.classList.remove('selected'));
        document.querySelector('#recordTypeFilter .filter-option:first-child').classList.add('selected');

        state.visits.currentPage = 1;
        updateTable();
    };

    // 🔹 Items per page
    window.updateItemsPerPage = function() {
        state.visits.itemsPerPage = parseInt(document.getElementById('visitsItemsPerPage').value);
        state.visits.currentPage = 1;
        updateTable();
    };

    // 🔹 Pagination
    window.changePage = function(direction) {
        const totalRows = getVisibleRows().length;
        const totalPages = Math.ceil(totalRows / state.visits.itemsPerPage);
        state.visits.currentPage = Math.min(Math.max(1, state.visits.currentPage + direction), totalPages);
        updateTable();
    };

    // Initialize
    updateTable();
});
