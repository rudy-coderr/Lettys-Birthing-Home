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

    // Close filter dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const filterDropdowns = document.querySelectorAll('.filter-dropdown-menu');
        const filterButtons = document.querySelectorAll('.filter-btn');
        if (!Array.from(filterButtons).some(btn => btn.contains(event.target)) &&
            !Array.from(filterDropdowns).some(dropdown => dropdown.contains(event.target))) {
            filterDropdowns.forEach(dropdown => dropdown.classList.remove('show'));
        }
    });

    // Normalize branch names for consistent comparison
    function normalizeBranchName(name) {
        return name
            .trim()
            .toLowerCase()
            .replace(/\./g, '') // Remove periods (e.g., "Sta." -> "Sta")
            .replace(/\s+/g, ' '); // Normalize multiple spaces to single space
    }

    // Search and filter functionality
    let currentPage = 1;
    let itemsPerPage = parseInt(document.getElementById('currentItemsPerPage').value);
    let branchFilter = 'all';
    let searchQuery = '';

    function getVisibleRows() {
        const rows = Array.from(document.querySelectorAll(
            '#currentPatientsTable tbody tr:not(.no-results)'
        ));
        return rows.filter(row => {
            const id = row.querySelector('.patient-id').textContent.toLowerCase();
            const name = row.querySelector('.patient-name').textContent.toLowerCase();
            const address = row.querySelector('.patient-address').textContent.toLowerCase();
            const phone = row.querySelector('.patient-phone').textContent.toLowerCase();
            const branch = normalizeBranchName(row.querySelector('.patient-branch').textContent);
            const consulted = row.querySelector('.patient-consulted').textContent.toLowerCase();

            // Search filter
            const matchesSearch = searchQuery === '' ||
                id.includes(searchQuery) ||
                name.includes(searchQuery) ||
                address.includes(searchQuery) ||
                phone.includes(searchQuery) ||
                branch.includes(searchQuery) ||
                consulted.includes(searchQuery);

            // Branch filter
            const matchesBranch = branchFilter === 'all' || branch === normalizeBranchName(branchFilter);

            return matchesSearch && matchesBranch;
        });
    }

    function updateTable() {
        const rows = Array.from(document.querySelectorAll(
            '#currentPatientsTable tbody tr:not(.no-results)'
        ));
        const visibleRows = getVisibleRows();
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);

        // Update filter count
        const filterCount = document.getElementById('branchFilterCountCurrent');
        filterCount.textContent = branchFilter !== 'all' ? totalRows : '0';
        filterCount.style.display = branchFilter !== 'all' ? 'inline' : 'none';
        document.getElementById('clearFiltersBtnCurrent').style.display =
            branchFilter !== 'all' || searchQuery ? 'block' : 'none';

        // Update pagination
        currentPage = Math.min(currentPage, Math.max(1, totalPages));
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Show/hide rows
        rows.forEach(row => row.style.display = 'none');
        visibleRows.slice(start, end).forEach(row => row.style.display = '');

        // Update no results message
        const noResults = document.getElementById('noResultsCurrent');
        const hasTableRows = rows.length > 0;

        if (hasTableRows && totalRows === 0) {
            // May patients sa DB pero walang nagmatch sa search/filter
            noResults.style.display = 'block';
        } else {
            // Either may results OR walang patients talaga (Blade handles that case)
            noResults.style.display = 'none';
        }

        // Update pagination controls
        const prevBtn = document.getElementById('currentPrevPage');
        const nextBtn = document.getElementById('currentNextPage');
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;

        const pageNumbers = document.getElementById('currentPageNumbers');
        pageNumbers.textContent = totalPages > 0
            ? `Page ${currentPage} of ${totalPages}`
            : 'Page 0 of 0';
    }

    window.searchPatients = function(type) {
        if (type === 'current') {
            searchQuery = document.getElementById('searchInputCurrent').value.toLowerCase();
            currentPage = 1;
            updateTable();
        }
    };

    window.filterPatients = function(category, value, type) {
        if (type === 'current' && category === 'branch') {
            branchFilter = value;
            const options = document.querySelectorAll('#branchFilterCurrent .filter-option');
            options.forEach(opt => {
                opt.classList.remove('selected');
                if (normalizeBranchName(opt.textContent) === normalizeBranchName(value)) {
                    opt.classList.add('selected');
                }
            });
            document.getElementById('branchFilterCurrent').classList.remove('show');
            currentPage = 1;
            updateTable();
        }
    };

    window.clearAllFilters = function(type) {
        if (type === 'current') {
            searchQuery = '';
            branchFilter = 'all';
            document.getElementById('searchInputCurrent').value = '';
            const options = document.querySelectorAll('#branchFilterCurrent .filter-option');
            options.forEach(opt => opt.classList.remove('selected'));
            options[0].classList.add('selected');
            currentPage = 1;
            updateTable();
        }
    };

    window.updateItemsPerPage = function(type) {
        if (type === 'current') {
            itemsPerPage = parseInt(document.getElementById('currentItemsPerPage').value);
            currentPage = 1;
            updateTable();
        }
    };

    window.changePage = function(type, direction) {
        if (type === 'current') {
            const totalRows = getVisibleRows().length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
            updateTable();
        }
    };

    // Initialize table
    updateTable();
});
