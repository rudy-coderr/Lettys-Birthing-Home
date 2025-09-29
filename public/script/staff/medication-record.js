
       
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
            let itemsPerPage = parseInt(document.getElementById('medicationItemsPerPage').value);
            let categoryFilter = 'all';
            let searchQuery = '';

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll(
                    '#medicationTable tbody tr:not(.no-results):not(.no-medications)'));
                return rows.filter(row => {
                    const patientName = row.querySelector('.patient-name').textContent.toLowerCase();
                    const category = row.querySelector('.medication-category').textContent.toLowerCase();
                    const itemName = row.querySelector('.item-name').textContent.toLowerCase();
                    const quantity = row.querySelector('.quantity').textContent.toLowerCase();
                    const remarks = row.querySelector('.remarks').textContent.toLowerCase();

                    // Search filter
                    const matchesSearch = searchQuery === '' ||
                        patientName.includes(searchQuery) ||
                        category.includes(searchQuery) ||
                        itemName.includes(searchQuery) ||
                        quantity.includes(searchQuery) ||
                        remarks.includes(searchQuery);

                    // Category filter
                    const matchesCategory = categoryFilter === 'all' || category === categoryFilter
                        .toLowerCase();

                    return matchesSearch && matchesCategory;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll(
                    '#medicationTable tbody tr:not(.no-results):not(.no-medications)'));
                const noMedicationsRow = document.querySelector('#medicationTable tbody tr.no-medications');
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);
                const isFiltered = searchQuery !== '' || categoryFilter !== 'all';

                // Update filter count
                const filterCount = document.getElementById('categoryFilterCountMedication');
                filterCount.textContent = categoryFilter !== 'all' ? totalRows : '0';
                filterCount.style.display = categoryFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnMedication').style.display = isFiltered ? 'block' : 'none';

                // Update pagination
                currentPage = Math.min(currentPage, Math.max(1, totalPages));
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results/no medications message
                const noResults = document.getElementById('noResultsMedication');
                if (noMedicationsRow) {
                    noMedicationsRow.style.display = rows.length === 0 && !isFiltered ? '' : 'none';
                }
                noResults.style.display = totalRows === 0 && isFiltered ? 'block' : 'none';

                // Update pagination controls
                const prevBtn = document.getElementById('medicationPrevPage');
                const nextBtn = document.getElementById('medicationNextPage');
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('medicationPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchMedications = function() {
                searchQuery = document.getElementById('searchInputMedication').value.toLowerCase();
                currentPage = 1;
                updateTable();
            };

            window.filterMedications = function(category, value) {
                if (category === 'category') {
                    categoryFilter = value;
                    const options = document.querySelectorAll('#categoryFilterMedication .filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    event.target.classList.add('selected');
                    document.getElementById('categoryFilterMedication').classList.remove('show');
                    currentPage = 1;
                    updateTable();
                }
            };

            window.clearAllFilters = function() {
                searchQuery = '';
                categoryFilter = 'all';
                document.getElementById('searchInputMedication').value = '';
                const options = document.querySelectorAll('#categoryFilterMedication .filter-option');
                options.forEach(opt => opt.classList.remove('selected'));
                options[0].classList.add('selected');
                currentPage = 1;
                updateTable();
            };

            window.updateItemsPerPage = function() {
                itemsPerPage = parseInt(document.getElementById('medicationItemsPerPage').value);
                currentPage = 1;
                updateTable();
            };

            window.changePage = function(direction) {
                const totalRows = getVisibleRows().length;
                const totalPages = Math.ceil(totalRows / itemsPerPage);
                currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
                updateTable();
            };

            window.openDeleteModal = function(medicationId) {
                document.getElementById('deleteMedicationId').textContent = medicationId;
                document.getElementById('deleteMedicationCode').value = medicationId;
                const modal = new bootstrap.Modal(document.getElementById('deleteMedicationModal'));
                modal.show();
            };

            // Form submission handling (for demo purposes, logs to console)
            document.getElementById('addMedicationForm').addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Add Medication Form Submitted:', new FormData(this));
                Swal.fire({
                    icon: 'success',
                    title: 'Medication Added',
                    text: 'The medication record has been added successfully!',
                    confirmButtonText: 'OK'
                });
            });

          


            // Initialize table
            updateTable();
        });
  