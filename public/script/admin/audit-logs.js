 

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

            // Search and filter functionality
            let currentPage = 1;
            let itemsPerPage = parseInt(document.getElementById('logsItemsPerPage').value);
            let actionFilter = 'all';
            let searchQuery = '';

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll('#auditLogsTable tbody tr:not(.no-results)'));
                return rows.filter(row => {
                    const staffName = row.querySelector('.staff-name').textContent.toLowerCase();
                    const action = row.querySelector('.action').textContent.toLowerCase();
                    const details = row.querySelector('.details').textContent.toLowerCase();

                    // Search filter
                    const matchesSearch = searchQuery === '' ||
                        staffName.includes(searchQuery) ||
                        action.includes(searchQuery) ||
                        details.includes(searchQuery);

                    // Action filter
                    let matchesAction = true;
                    if (actionFilter !== 'all') {
                        matchesAction = action === actionFilter;
                    }

                    return matchesSearch && matchesAction;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll('#auditLogsTable tbody tr:not(.no-results)'));
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);

                // Update filter count
                const filterCount = document.getElementById('actionFilterCountLogs');
                filterCount.textContent = actionFilter !== 'all' ? totalRows : '0';
                filterCount.style.display = actionFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnLogs').style.display = actionFilter !== 'all' ||
                    searchQuery ? 'block' : 'none';

                // Update pagination
                currentPage = Math.min(currentPage, Math.max(1, totalPages));
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results message
                const noResults = document.getElementById('noResultsLogs');
                noResults.style.display = totalRows === 0 ? 'block' : 'none';

                // Update pagination controls
                const prevBtn = document.getElementById('logsPrevPage');
                const nextBtn = document.getElementById('logsNextPage');
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('logsPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchLogs = function() {
                searchQuery = document.getElementById('searchInputLogs').value.toLowerCase();
                currentPage = 1;
                updateTable();
            };

            window.filterLogs = function(category, value) {
                if (category === 'action') {
                    actionFilter = value;
                    const options = document.querySelectorAll('#actionFilterLogs .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    event.target.classList.add('selected');
                    document.getElementById('actionFilterLogs').classList.remove('show');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.clearAllFilters = function(type) {
                if (type === 'logs') {
                    searchQuery = '';
                    actionFilter = 'all';
                    document.getElementById('searchInputLogs').value = '';
                    const options = document.querySelectorAll('#actionFilterLogs .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    options[0].classList.add('selected');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.updateItemsPerPage = function(type) {
                if (type === 'logs') {
                    itemsPerPage = parseInt(document.getElementById('logsItemsPerPage').value);
                    currentPage = 1;
                    updateTable();
                }
            };

            window.changePage = function(type, direction) {
                if (type === 'logs') {
                    const totalRows = getVisibleRows().length;
                    const totalPages = Math.ceil(totalRows / itemsPerPage);
                    currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
                    updateTable();
                }
            };

            // Initialize table
            updateTable();
        });
  