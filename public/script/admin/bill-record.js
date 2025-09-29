
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

            // State management
            const state = {
                receipts: {
                    currentPage: 1,
                    itemsPerPage: 10,
                    statusFilter: 'all',
                    searchQuery: ''
                }
            };

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll(
                    '#receiptsTable tbody tr:not(.no-results):not(.no-bills)'));
                return rows.filter(row => {
                    const billCode = row.querySelector('.bill-code')?.textContent.toLowerCase() || '';
                    const patientName = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
                    const receiptNumber = row.querySelector('.bill-receipt')?.textContent.toLowerCase() || '';
                    const dateGenerated = row.querySelector('.date-generated')?.textContent.toLowerCase() || '';
                    const generatedBy = row.querySelector('.generated-by')?.textContent.toLowerCase() || '';
                    const status = row.dataset.status?.toLowerCase() || '';

                    const matchesSearch = state.receipts.searchQuery === '' ||
                        billCode.includes(state.receipts.searchQuery) ||
                        patientName.includes(state.receipts.searchQuery) ||
                        receiptNumber.includes(state.receipts.searchQuery) ||
                        dateGenerated.includes(state.receipts.searchQuery) ||
                        generatedBy.includes(state.receipts.searchQuery);

                    const matchesStatus = state.receipts.statusFilter === 'all' || status === state.receipts.statusFilter;

                    return matchesSearch && matchesStatus;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll(
                    '#receiptsTable tbody tr:not(.no-results):not(.no-bills)'));
                const noBillsRow = document.querySelector('#receiptsTable tbody tr.no-bills');
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / state.receipts.itemsPerPage);
                const totalReceipts = rows.length;

                // Update filter count
                const filterCount = document.getElementById('statusFilterCountReceipts');
                filterCount.textContent = state.receipts.statusFilter !== 'all' ? totalRows : '0';
                filterCount.style.display = state.receipts.statusFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnReceipts').style.display =
                    state.receipts.statusFilter !== 'all' || state.receipts.searchQuery ? 'block' : 'none';

                // Update pagination
                state.receipts.currentPage = Math.min(state.receipts.currentPage, Math.max(1, totalPages));
                const start = (state.receipts.currentPage - 1) * state.receipts.itemsPerPage;
                const end = start + state.receipts.itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results/no bills message
                const noResults = document.getElementById('noResultsReceipts');
                if (totalReceipts === 0) {
                    noResults.style.display = 'none';
                    if (noBillsRow) noBillsRow.style.display = '';
                } else {
                    noResults.style.display = totalRows === 0 ? 'block' : 'none';
                    if (noBillsRow) noBillsRow.style.display = 'none';
                }

                // Update pagination controls
                const prevBtn = document.getElementById('receiptsPrevPage');
                const nextBtn = document.getElementById('receiptsNextPage');
                prevBtn.disabled = state.receipts.currentPage === 1;
                nextBtn.disabled = state.receipts.currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('receiptsPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${state.receipts.currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchReceipts = function() {
                state.receipts.searchQuery = document.getElementById('searchInputReceipts').value.toLowerCase();
                state.receipts.currentPage = 1;
                updateTable();
            };

            window.filterReceipts = function(category, value) {
                if (category === 'status') {
                    state.receipts.statusFilter = value;
                    const options = document.querySelectorAll('#statusFilterReceipts .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    event.target.classList.add('selected');
                    document.getElementById('statusFilterReceipts').classList.remove('show');
                    state.receipts.currentPage = 1;
                    updateTable();
                }
            };

            window.clearAllFilters = function() {
                state.receipts.searchQuery = '';
                state.receipts.statusFilter = 'all';
                document.getElementById('searchInputReceipts').value = '';
                const options = document.querySelectorAll('#statusFilterReceipts .filter-option');
                options.forEach(opt => opt.classList.remove('selected'));
                options[0].classList.add('selected');
                state.receipts.currentPage = 1;
                updateTable();
            };

            window.updateItemsPerPage = function() {
                state.receipts.itemsPerPage = parseInt(document.getElementById('receiptsItemsPerPage').value);
                state.receipts.currentPage = 1;
                updateTable();
            };

            window.changePage = function(direction) {
                const totalRows = getVisibleRows().length;
                const totalPages = Math.ceil(totalRows / state.receipts.itemsPerPage);
                state.receipts.currentPage = Math.min(Math.max(1, state.receipts.currentPage + direction), totalPages);
                updateTable();
            };


            // Initialize table
            updateTable();
        });
   