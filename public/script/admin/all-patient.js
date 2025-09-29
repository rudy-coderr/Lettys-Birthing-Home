
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
            let itemsPerPage = parseInt(document.getElementById('recordsItemsPerPage').value);
            let ageFilter = 'all';
            let searchQuery = '';

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll(
                    '#recordsPatientsTable tbody tr:not(.no-results)'));
                return rows.filter(row => {
                    const name = row.querySelector('.patient-name').textContent.toLowerCase();
                    const address = row.querySelector('.patient-address').textContent.toLowerCase();
                    const contact = row.querySelector('.patient-contact').textContent.toLowerCase();
                    const ageText = row.querySelector('.patient-age').textContent;
                    const age = parseInt(ageText) || 0;

                    // Search filter
                    const matchesSearch = searchQuery === '' ||
                        name.includes(searchQuery) ||
                        address.includes(searchQuery) ||
                        contact.includes(searchQuery);

                    // Age filter
                    let matchesAge = true;
                    if (ageFilter === 'young') matchesAge = age >= 18 && age <= 25;
                    else if (ageFilter === 'adult') matchesAge = age >= 26 && age <= 35;
                    else if (ageFilter === 'mature') matchesAge = age >= 36;

                    return matchesSearch && matchesAge;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll(
                    '#recordsPatientsTable tbody tr:not(.no-results)'));
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);

                // Update filter count
                const filterCount = document.getElementById('ageFilterCountRecords');
                filterCount.textContent = ageFilter !== 'all' ? totalRows : '0';
                filterCount.style.display = ageFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnRecords').style.display = ageFilter !== 'all' ||
                    searchQuery ? 'block' : 'none';

                // Update pagination
                currentPage = Math.min(currentPage, Math.max(1, totalPages));
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results message
                const noResults = document.getElementById('noResultsRecords');
                noResults.style.display = totalRows === 0 ? 'block' : 'none';

                // Update pagination controls
                const prevBtn = document.getElementById('recordsPrevPage');
                const nextBtn = document.getElementById('recordsNextPage');
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('recordsPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchPatients = function(type) {
                if (type === 'records') {
                    searchQuery = document.getElementById('searchInputRecords').value.toLowerCase();
                    currentPage = 1;
                    updateTable();
                }
            };

            window.filterPatients = function(category, value, type) {
                if (type === 'records' && category === 'age') {
                    ageFilter = value;
                    const options = document.querySelectorAll('#ageFilterRecords .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    event.target.classList.add('selected');
                    document.getElementById('ageFilterRecords').classList.remove('show');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.clearAllFilters = function(type) {
                if (type === 'records') {
                    searchQuery = '';
                    ageFilter = 'all';
                    document.getElementById('searchInputRecords').value = '';
                    const options = document.querySelectorAll('#ageFilterRecords .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    options[0].classList.add('selected');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.updateItemsPerPage = function(type) {
                if (type === 'records') {
                    itemsPerPage = parseInt(document.getElementById('recordsItemsPerPage').value);
                    currentPage = 1;
                    updateTable();
                }
            };

            window.changePage = function(type, direction) {
                if (type === 'records') {
                    const totalRows = getVisibleRows().length;
                    const totalPages = Math.ceil(totalRows / itemsPerPage);
                    currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
                    updateTable();
                }
            };

            // Initialize table
            updateTable();
        });
   