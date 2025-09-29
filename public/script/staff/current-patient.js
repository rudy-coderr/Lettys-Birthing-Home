
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
            let itemsPerPage = parseInt(document.getElementById('currentItemsPerPage').value);
            let ageFilter = 'all';
            let searchQuery = '';

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll(
                    '#currentPatientsTable tbody tr:not(.no-results)'));
                return rows.filter(row => {
                    const firstName = row.querySelector('.patient-name:nth-child(2)').textContent
                        .toLowerCase();
                    const lastName = row.querySelector('.patient-name:nth-child(3)').textContent
                        .toLowerCase();
                    const address = row.querySelector('.patient-address').textContent.toLowerCase();
                    const phone = row.querySelector('.patient-phone').textContent.toLowerCase();
                    const ageText = row.querySelector('.patient-age').textContent;
                    const age = parseInt(ageText) || 0;

                    // Search filter
                    const matchesSearch = searchQuery === '' ||
                        firstName.includes(searchQuery) ||
                        lastName.includes(searchQuery) ||
                        address.includes(searchQuery) ||
                        phone.includes(searchQuery);

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
                    '#currentPatientsTable tbody tr:not(.no-results)'));
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);

                // Update filter count
                const filterCount = document.getElementById('ageFilterCountCurrent');
                filterCount.textContent = ageFilter !== 'all' ? totalRows : '0';
                filterCount.style.display = ageFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnCurrent').style.display = ageFilter !== 'all' ||
                    searchQuery ? 'block' : 'none';

                // Update pagination
                currentPage = Math.min(currentPage, Math.max(1, totalPages));
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results message
                const noResults = document.getElementById('noResultsCurrent');
                const hasPatients = document.querySelectorAll('#currentPatientsTable tbody tr:not(.no-results)')
                    .length > 0;
                const isFiltered = searchQuery !== '' || ageFilter !== 'all';

                // Show noResultsCurrent only when there are patients but none match the search/filter
                noResults.style.display = hasPatients && totalRows === 0 && isFiltered ? 'block' : 'none';

                // Update pagination controls
                const prevBtn = document.getElementById('currentPrevPage');
                const nextBtn = document.getElementById('currentNextPage');
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('currentPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchPatients = function(type) {
                if (type === 'current') {
                    searchQuery = document.getElementById('searchInputCurrent').value.toLowerCase();
                    currentPage = 1;
                    updateTable();
                }
            };

            window.filterPatients = function(category, value, type) {
                if (type === 'current' && category === 'age') {
                    ageFilter = value;
                    const options = document.querySelectorAll('#ageFilterCurrent .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    event.target.classList.add('selected');
                    document.getElementById('ageFilterCurrent').classList.remove('show');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.clearAllFilters = function(type) {
                if (type === 'current') {
                    searchQuery = '';
                    ageFilter = 'all';
                    document.getElementById('searchInputCurrent').value = '';
                    const options = document.querySelectorAll('#ageFilterCurrent .filter-option');
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

            window.openDeleteModal = function(url, name) {
                document.getElementById('deletePatientName').textContent = name;
                document.getElementById('deleteForm').action = url;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            };

            // Initialize table
            updateTable();
        });
   