
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
                all: {
                    currentPage: 1,
                    itemsPerPage: 10,
                    reasonFilter: 'all',
                    branchFilter: 'all',
                    searchQuery: ''
                }
            };

            function getVisibleRows(type) {
                const tableId = type === 'all' ? '#appointmentsTableAll' : '';
                const rows = Array.from(document.querySelectorAll(`${tableId} tbody tr:not(.no-results)`));
                return rows.filter(row => {
                    const firstName = row.querySelector('.patient-first-name')?.textContent.toLowerCase() || '';
                    const lastName = row.querySelector('.patient-last-name')?.textContent.toLowerCase() || '';
                    const phone = row.querySelector('.patient-phone')?.textContent.toLowerCase() || '';
                    const branch = row.dataset.branch?.toLowerCase() || '';
                    const reason = row.querySelector('.appointment-reason')?.textContent.toLowerCase() || '';
                    const schedule = row.querySelector('.schedule-info')?.textContent.toLowerCase() || '';

                    const matchesSearch = state[type].searchQuery === '' ||
                        firstName.includes(state[type].searchQuery) ||
                        lastName.includes(state[type].searchQuery) ||
                        phone.includes(state[type].searchQuery) ||
                        branch.includes(state[type].searchQuery) ||
                        reason.includes(state[type].searchQuery) ||
                        schedule.includes(state[type].searchQuery);

                    const matchesReason = state[type].reasonFilter === 'all' || reason.includes(state[type].reasonFilter);
                    const matchesBranch = state[type].branchFilter === 'all' || branch === state[type].branchFilter;

                    return matchesSearch && matchesReason && matchesBranch;
                });
            }

            function updateTable(type) {
                const tableId = type === 'all' ? '#appointmentsTableAll' : '';
                const rows = Array.from(document.querySelectorAll(`${tableId} tbody tr:not(.no-results)`));
                const noAppointmentsRow = document.querySelector(`${tableId} tbody tr.no-results`);
                const visibleRows = getVisibleRows(type);
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / state[type].itemsPerPage);
                const totalAppointments = rows.length;

                // Update filter counts
                const reasonFilterCount = document.getElementById('reasonFilterCountAll');
                const branchFilterCount = document.getElementById('branchFilterCountAll');
                reasonFilterCount.textContent = state[type].reasonFilter !== 'all' ? totalRows : '0';
                branchFilterCount.textContent = state[type].branchFilter !== 'all' ? totalRows : '0';
                reasonFilterCount.style.display = state[type].reasonFilter !== 'all' ? 'inline' : 'none';
                branchFilterCount.style.display = state[type].branchFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnAll').style.display =
                    state[type].reasonFilter !== 'all' || state[type].branchFilter !== 'all' || state[type].searchQuery ? 'block' : 'none';

                // Update pagination
                state[type].currentPage = Math.min(state[type].currentPage, Math.max(1, totalPages));
                const start = (state[type].currentPage - 1) * state[type].itemsPerPage;
                const end = start + state[type].itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results/no appointments message
                const noResults = document.getElementById('noResultsAll');
                if (totalAppointments === 0) {
                    noResults.style.display = 'none';
                    if (noAppointmentsRow) noAppointmentsRow.style.display = '';
                } else {
                    noResults.style.display = totalRows === 0 ? 'block' : 'none';
                    if (noAppointmentsRow) noAppointmentsRow.style.display = 'none';
                }

                // Update pagination controls
                const prevBtn = document.getElementById('prevPageAll');
                const nextBtn = document.getElementById('nextPageAll');
                prevBtn.disabled = state[type].currentPage === 1;
                nextBtn.disabled = state[type].currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('pageNumbersAll');
                pageNumbers.textContent = totalPages > 0 ? `Page ${state[type].currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchAppointments = function(type) {
                if (type === 'all') {
                    state.all.searchQuery = document.getElementById('searchInputAll').value.toLowerCase();
                    state.all.currentPage = 1;
                    updateTable('all');
                }
            };

            window.filterAppointments = function(category, value, type) {
                if (type === 'all') {
                    const dropdownId = category === 'reason' ? 'reasonFilterAll' : 'branchFilterAll';
                    const dropdown = document.getElementById(dropdownId);
                    const options = dropdown.querySelectorAll('.filter-option');
                    options.forEach(opt => opt.classList.remove('selected'));
                    const selectedOption = Array.from(options).find(opt => opt.textContent.toLowerCase().includes(value.toLowerCase()) || value === 'all');
                    if (selectedOption) {
                        selectedOption.classList.add('selected');
                    }
                    dropdown.classList.remove('show');

                    if (category === 'reason') {
                        state.all.reasonFilter = value;
                    } else if (category === 'branch') {
                        state.all.branchFilter = value;
                    }

                    state.all.currentPage = 1;
                    updateTable('all');
                }
            };

            window.clearAllFilters = function(type) {
                if (type === 'all') {
                    state.all.searchQuery = '';
                    state.all.reasonFilter = 'all';
                    state.all.branchFilter = 'all';
                    document.getElementById('searchInputAll').value = '';
                    document.querySelectorAll('#reasonFilterAll .filter-option').forEach(opt => opt.classList.remove('selected'));
                    document.querySelector('#reasonFilterAll .filter-option:first-child').classList.add('selected');
                    document.querySelectorAll('#branchFilterAll .filter-option').forEach(opt => opt.classList.remove('selected'));
                    document.querySelector('#branchFilterAll .filter-option:first-child').classList.add('selected');
                    state.all.currentPage = 1;
                    updateTable('all');
                }
            };

            window.updateItemsPerPage = function(type) {
                if (type === 'all') {
                    state.all.itemsPerPage = parseInt(document.getElementById('itemsPerPageAll').value);
                    state.all.currentPage = 1;
                    updateTable('all');
                }
            };

            window.changePage = function(type, direction) {
                if (type === 'all') {
                    const totalRows = getVisibleRows(type).length;
                    const totalPages = Math.ceil(totalRows / state[type].itemsPerPage);
                    state[type].currentPage = Math.min(Math.max(1, state[type].currentPage + direction), totalPages);
                    updateTable(type);
                }
            };

            window.openRescheduleModal = function(button) {
                const id = button.getAttribute('data-id');
                const date = button.getAttribute('data-date');
                const time = button.getAttribute('data-time');
                document.getElementById('rescheduleAppointmentId').value = id;
                document.getElementById('rescheduleDate').value = date;
                document.getElementById('rescheduleTime').value = time;
                const modal = new bootstrap.Modal(document.getElementById('rescheduleAppointmentModal'));
                modal.show();
            };

            window.openCancelModal = function(id) {
                document.getElementById('cancelAppointmentId').value = id;
                const modal = new bootstrap.Modal(document.getElementById('cancelAppointmentModal'));
                modal.show();
            };

            window.sendMessage = function(phone) {
                if (phone === 'N/A') {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Phone Number',
                        text: 'No phone number available for this client.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: 'var(--primary-color)'
                    });
                    return;
                }
                document.getElementById('messagePhone').value = phone;
                const modal = new bootstrap.Modal(document.getElementById('sendMessageModal'));
                modal.show();
            };


            // Initialize table
            updateTable('all');
        });
    