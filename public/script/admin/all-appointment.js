
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

            // State management
            const state = {
                appointments: {
                    currentPage: 1,
                    itemsPerPage: 10,
                    statusFilter: 'all',
                    branchFilter: 'all',
                    searchQuery: ''
                }
            };

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll(
                    '#appointmentsTable tbody tr:not(.no-results)'));
                return rows.filter(row => {
                    const firstName = row.querySelector('.appointment-first-name')?.textContent.toLowerCase() || '';
                    const lastName = row.querySelector('.appointment-last-name')?.textContent.toLowerCase() || '';
                    const phone = row.querySelector('.appointment-phone')?.textContent.toLowerCase() || '';
                    const branch = row.dataset.branch?.toLowerCase() || '';
                    const reason = row.querySelector('.appointment-reason')?.textContent.toLowerCase() || '';
                    const schedule = row.querySelector('.appointment-schedule')?.textContent.toLowerCase() || '';
                    const status = row.dataset.status?.toLowerCase() || '';

                    const matchesSearch = state.appointments.searchQuery === '' ||
                        firstName.includes(state.appointments.searchQuery) ||
                        lastName.includes(state.appointments.searchQuery) ||
                        phone.includes(state.appointments.searchQuery) ||
                        branch.includes(state.appointments.searchQuery) ||
                        reason.includes(state.appointments.searchQuery) ||
                        schedule.includes(state.appointments.searchQuery);

                    const matchesStatus = state.appointments.statusFilter === 'all' || status === state.appointments.statusFilter;
                    const matchesBranch = state.appointments.branchFilter === 'all' || branch === state.appointments.branchFilter;

                    return matchesSearch && matchesStatus && matchesBranch;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll(
                    '#appointmentsTable tbody tr:not(.no-results)'));
                const noAppointmentsRow = document.querySelector('#appointmentsTable tbody tr.no-results');
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / state.appointments.itemsPerPage);
                const totalAppointments = rows.length;

                // Update filter counts
                const statusFilterCount = document.getElementById('filterCountStatus');
                const branchFilterCount = document.getElementById('filterCountBranch');
                statusFilterCount.textContent = state.appointments.statusFilter !== 'all' ? totalRows : '0';
                branchFilterCount.textContent = state.appointments.branchFilter !== 'all' ? totalRows : '0';
                statusFilterCount.style.display = state.appointments.statusFilter !== 'all' ? 'inline' : 'none';
                branchFilterCount.style.display = state.appointments.branchFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtnAppointments').style.display =
                    state.appointments.statusFilter !== 'all' || state.appointments.branchFilter !== 'all' || state.appointments.searchQuery ? 'block' : 'none';

                // Update pagination
                state.appointments.currentPage = Math.min(state.appointments.currentPage, Math.max(1, totalPages));
                const start = (state.appointments.currentPage - 1) * state.appointments.itemsPerPage;
                const end = start + state.appointments.itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results/no appointments message
                const noResults = document.getElementById('noResultsAppointments');
                if (totalAppointments === 0) {
                    noResults.style.display = 'none';
                    if (noAppointmentsRow) noAppointmentsRow.style.display = '';
                } else {
                    noResults.style.display = totalRows === 0 ? 'block' : 'none';
                    if (noAppointmentsRow) noAppointmentsRow.style.display = 'none';
                }

                // Update pagination controls
                const prevBtn = document.getElementById('appointmentsPrevPage');
                const nextBtn = document.getElementById('appointmentsNextPage');
                prevBtn.disabled = state.appointments.currentPage === 1;
                nextBtn.disabled = state.appointments.currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('appointmentsPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${state.appointments.currentPage} of ${totalPages}` : 'Page 0 of 0';
            }

            window.searchAppointments = function() {
                state.appointments.searchQuery = document.getElementById('searchInputAppointments').value.toLowerCase();
                state.appointments.currentPage = 1;
                updateTable();
            };

            window.filterAppointments = function(category, value) {
                const dropdownId = category === 'status' ? 'filterDropdownStatus' : 'filterDropdownBranch';
                const dropdown = document.getElementById(dropdownId);
                const options = dropdown.querySelectorAll('.filter-option');
                options.forEach(opt => opt.classList.remove('selected'));
                const selectedOption = Array.from(options).find(opt => opt.textContent.toLowerCase().includes(value.toLowerCase()) || value === 'all');
                if (selectedOption) {
                    selectedOption.classList.add('selected');
                }
                dropdown.classList.remove('show');

                if (category === 'status') {
                    state.appointments.statusFilter = value;
                } else if (category === 'branch') {
                    state.appointments.branchFilter = value;
                }

                state.appointments.currentPage = 1;
                updateTable();
            };

            window.clearAllFilters = function() {
                state.appointments.searchQuery = '';
                state.appointments.statusFilter = 'all';
                state.appointments.branchFilter = 'all';
                document.getElementById('searchInputAppointments').value = '';
                document.querySelectorAll('#filterDropdownStatus .filter-option').forEach(opt => opt.classList.remove('selected'));
                document.querySelector('#filterDropdownStatus .filter-option:first-child').classList.add('selected');
                document.querySelectorAll('#filterDropdownBranch .filter-option').forEach(opt => opt.classList.remove('selected'));
                document.querySelector('#filterDropdownBranch .filter-option:first-child').classList.add('selected');
                state.appointments.currentPage = 1;
                updateTable();
            };

            window.updateItemsPerPage = function() {
                state.appointments.itemsPerPage = parseInt(document.getElementById('appointmentsItemsPerPage').value);
                state.appointments.currentPage = 1;
                updateTable();
            };

            window.changePage = function(direction) {
                const totalRows = getVisibleRows().length;
                const totalPages = Math.ceil(totalRows / state.appointments.itemsPerPage);
                state.appointments.currentPage = Math.min(Math.max(1, state.appointments.currentPage + direction), totalPages);
                updateTable();
            };

            window.editAppointment = function(id) {
                window.location.href = `{{ url('appointments') }}/${id}/edit`;
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

            window.openDeleteModal = function(id) {
                document.getElementById('deleteAppointmentId').value = id;
                const modal = new bootstrap.Modal(document.getElementById('deleteAppointmentModal'));
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
            updateTable();
        });
