
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
            let itemsPerPage = parseInt(document.getElementById('itemsPerPageAll').value);
            let reasonFilter = 'all';
            let searchQuery = '';

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll('#appointmentsTableAll tbody tr:not(.no-results)'));
                return rows.filter(row => {
                    const firstName = row.querySelector('.patient-first-name').textContent.toLowerCase();
                    const lastName = row.querySelector('.patient-last-name').textContent.toLowerCase();
                    const phone = row.querySelector('.patient-phone').textContent.toLowerCase();
                    const reason = row.querySelector('.appointment-reason').textContent.toLowerCase();

                    // Search filter
                    const matchesSearch = searchQuery === '' ||
                        firstName.includes(searchQuery) ||
                        lastName.includes(searchQuery) ||
                        phone.includes(searchQuery);

                    // Reason filter
                    const matchesReason = reasonFilter === 'all' || reason.includes(reasonFilter);

                    return matchesSearch && matchesReason;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll('#appointmentsTableAll tbody tr:not(.no-results)'));
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);

                // Update filter count
                const filterCount = document.getElementById('reasonFilterCountAll');
                filterCount.textContent = reasonFilter !== 'all' ? totalRows : '0';
                filterCount.style.display = reasonFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnAll').style.display = reasonFilter !== 'all' || searchQuery ? 'block' : 'none';

                // Update pagination
                currentPage = Math.min(currentPage, Math.max(1, totalPages));
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results message
                const noResults = document.getElementById('noResultsAll');
                const hasAppointments = document.querySelectorAll('#appointmentsTableAll tbody tr:not(.no-results)').length > 0;
                const isFiltered = searchQuery !== '' || reasonFilter !== 'all';

                // Show noResultsAll only when there are appointments but none match the search/filter
                noResults.style.display = hasAppointments && totalRows === 0 && isFiltered ? 'block' : 'none';

                // Update pagination controls
                const prevBtn = document.getElementById('prevPageAll');
                const nextBtn = document.getElementById('nextPageAll');
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('pageNumbersAll');
                pageNumbers.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchAppointments = function(type) {
                if (type === 'all') {
                    searchQuery = document.getElementById('searchInputAll').value.toLowerCase();
                    currentPage = 1;
                    updateTable();
                }
            };

            window.filterAppointments = function(category, value, type) {
                if (type === 'all' && category === 'reason') {
                    reasonFilter = value;
                    const options = document.querySelectorAll('#reasonFilterAll .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    event.target.classList.add('selected');
                    document.getElementById('reasonFilterAll').classList.remove('show');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.clearAllFilters = function(type) {
                if (type === 'all') {
                    searchQuery = '';
                    reasonFilter = 'all';
                    document.getElementById('searchInputAll').value = '';
                    const options = document.querySelectorAll('#reasonFilterAll .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    options[0].classList.add('selected');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.updateItemsPerPage = function(type) {
                if (type === 'all') {
                    itemsPerPage = parseInt(document.getElementById('itemsPerPageAll').value);
                    currentPage = 1;
                    updateTable();
                }
            };

            window.changePage = function(type, direction) {
                if (type === 'all') {
                    const totalRows = getVisibleRows().length;
                    const totalPages = Math.ceil(totalRows / itemsPerPage);
                    currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
                    updateTable();
                }
            };

            // Initialize table
            updateTable();
        });
  