

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

            // ===============================
            // State Management for Staff Table
            // ===============================
            const state = {
                staff: {
                    currentPage: 1,
                    itemsPerPage: 10,
                    workdaysFilter: 'all',
                    branchFilter: 'all',
                    searchQuery: ''
                }
            };

            function getVisibleRows() {
                const rows = Array.from(document.querySelectorAll('#staffTable tbody tr:not(.no-results)'));
                return rows.filter(row => {
                    const id = row.querySelector('.staff-id')?.textContent.toLowerCase() || '';
                    const name = row.querySelector('.staff-name')?.textContent.toLowerCase() || '';
                    const email = row.querySelector('.staff-email')?.textContent.toLowerCase() || '';
                    const address = row.querySelector('.staff-address')?.textContent.toLowerCase() || '';
                    const shift = row.querySelector('.staff-shift')?.textContent.toLowerCase() || '';
                    const workdays = row.dataset.workdays?.toLowerCase() || '';
                    const branch = row.dataset.branch?.toLowerCase() || '';

                    const matchesSearch = state.staff.searchQuery === '' ||
                        id.includes(state.staff.searchQuery) ||
                        name.includes(state.staff.searchQuery) ||
                        email.includes(state.staff.searchQuery) ||
                        address.includes(state.staff.searchQuery) ||
                        shift.includes(state.staff.searchQuery);

                    const matchesWorkdays = state.staff.workdaysFilter === 'all' ||
                        workdays.includes(state.staff.workdaysFilter);
                    const matchesBranch = state.staff.branchFilter === 'all' ||
                        branch === state.staff.branchFilter.toLowerCase();

                    return matchesSearch && matchesWorkdays && matchesBranch;
                });
            }

            function updateTable() {
                const rows = Array.from(document.querySelectorAll('#staffTable tbody tr:not(.no-results)'));
                const noStaffRow = document.querySelector('#staffTable tbody tr.no-results');
                const visibleRows = getVisibleRows();
                const totalRows = visibleRows.length;
                const totalPages = Math.ceil(totalRows / state.staff.itemsPerPage);
                const totalStaff = rows.length;

                // Update filter counts
                const workdaysFilterCount = document.getElementById('workdaysFilterCount');
                const branchFilterCount = document.getElementById('branchFilterCount');
                workdaysFilterCount.textContent = state.staff.workdaysFilter !== 'all' ? totalRows : '0';
                branchFilterCount.textContent = state.staff.branchFilter !== 'all' ? totalRows : '0';
                workdaysFilterCount.style.display = state.staff.workdaysFilter !== 'all' ? 'inline' : 'none';
                branchFilterCount.style.display = state.staff.branchFilter !== 'all' ? 'inline' : 'none';
                document.getElementById('clearFiltersBtn').style.display =
                    state.staff.workdaysFilter !== 'all' || state.staff.branchFilter !== 'all' ||
                    state.staff.searchQuery ? 'block' : 'none';

                // Update pagination
                state.staff.currentPage = Math.min(state.staff.currentPage, Math.max(1, totalPages));
                const start = (state.staff.currentPage - 1) * state.staff.itemsPerPage;
                const end = start + state.staff.itemsPerPage;

                // Show/hide rows
                rows.forEach(row => row.style.display = 'none');
                visibleRows.slice(start, end).forEach(row => row.style.display = '');

                // Update no results/no staff message
                const noResults = document.getElementById('noResults');
                if (totalStaff === 0) {
                    noResults.style.display = 'none';
                    if (noStaffRow) noStaffRow.style.display = '';
                } else {
                    noResults.style.display = totalRows === 0 ? 'block' : 'none';
                    if (noStaffRow) noStaffRow.style.display = 'none';
                }

                // Update pagination controls
                const prevBtn = document.getElementById('staffPrevPage');
                const nextBtn = document.getElementById('staffNextPage');
                prevBtn.disabled = state.staff.currentPage === 1;
                nextBtn.disabled = state.staff.currentPage === totalPages || totalPages === 0;

                const pageNumbers = document.getElementById('staffPageNumbers');
                pageNumbers.textContent = totalPages > 0 ? `Page ${state.staff.currentPage} of ${totalPages}` :
                    'Page 0 of 0';
            }

            // Search + Filter functions
            window.searchStaff = function() {
                state.staff.searchQuery = document.getElementById('searchInput').value.toLowerCase();
                state.staff.currentPage = 1;
                updateTable();
            };

            window.filterStaff = function(category, value) {
                const dropdownId = category === 'workdays' ? 'workdaysFilter' : 'branchFilter';
                const dropdown = document.getElementById(dropdownId);
                const options = dropdown.querySelectorAll('.filter-option');
                options.forEach(opt => opt.classList.remove('selected'));
                const selectedOption = Array.from(options).find(opt => opt.textContent.toLowerCase().includes(
                    value.toLowerCase()) || value === 'all');
                if (selectedOption) selectedOption.classList.add('selected');
                dropdown.classList.remove('show');

                if (category === 'workdays') state.staff.workdaysFilter = value.toLowerCase();
                if (category === 'branch') state.staff.branchFilter = value.toLowerCase();

                state.staff.currentPage = 1;
                updateTable();
            };

            window.clearAllFilters = function() {
                state.staff.searchQuery = '';
                state.staff.workdaysFilter = 'all';
                state.staff.branchFilter = 'all';
                document.getElementById('searchInput').value = '';
                document.querySelectorAll('#workdaysFilter .filter-option').forEach(opt => opt.classList.remove(
                    'selected'));
                document.querySelector('#workdaysFilter .filter-option:first-child').classList.add('selected');
                document.querySelectorAll('#branchFilter .filter-option').forEach(opt => opt.classList.remove(
                    'selected'));
                document.querySelector('#branchFilter .filter-option:first-child').classList.add('selected');
                state.staff.currentPage = 1;
                updateTable();
            };

            window.updateItemsPerPage = function() {
                state.staff.itemsPerPage = parseInt(document.getElementById('staffItemsPerPage').value);
                state.staff.currentPage = 1;
                updateTable();
            };

            window.changePage = function(direction) {
                const totalRows = getVisibleRows().length;
                const totalPages = Math.ceil(totalRows / state.staff.itemsPerPage);
                state.staff.currentPage = Math.min(Math.max(1, state.staff.currentPage + direction),
                totalPages);
                updateTable();
            };

            // ===============================
            // Modal Functions
            // ===============================
            window.openImageModal = function(src, name) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                modalImage.src = src;
                modalImage.alt = name;
                modal.classList.add('show');
            };

            window.closeImageModal = function() {
                document.getElementById('imageModal').classList.remove('show');
            };
            window.openDeleteModal = function(url, name) {
                document.getElementById('deleteStaffName').textContent = name;
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = url; // dynamically set action
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            };

            window.openStatusModal = function(actionUrl, staffName, isActive) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const message = document.getElementById('statusModalMessage');
    const confirmBtn = document.getElementById('statusConfirmBtn');

    form.action = actionUrl;

    if (isActive) {
        message.innerHTML = `Are you sure you want to <strong>Deactivate</strong> ${staffName}?`;
        confirmBtn.classList.remove('btn-success');
        confirmBtn.classList.add('btn-danger');
        confirmBtn.textContent = 'Deactivate';
    } else {
        message.innerHTML = `Are you sure you want to <strong>Activate</strong> ${staffName}?`;
        confirmBtn.classList.remove('btn-danger');
        confirmBtn.classList.add('btn-success');
        confirmBtn.textContent = 'Activate';
    }

    modal.show();
};



            // ✅ Fixed schedule route + form validation
        window.setSchedule = function (id, name, shift, workDays) {
            document.getElementById("scheduleStaffName").textContent = name;
            document.getElementById("scheduleStaffId").value = id;

            // ✅ Get base URL from data-action and replace ":id"
            const form = document.getElementById("scheduleForm");
            const urlTemplate = form.getAttribute("data-action");
            form.action = urlTemplate.replace(":id", id);

            // Set shift
            document.getElementById("shiftSelect").value = shift || "day";

    // Reset and set workdays
    const workDaysArray = workDays
        ? workDays.split(",").map(day => day.trim().toLowerCase())
        : [];
    document.querySelectorAll('.checkbox-group input[type="checkbox"]').forEach(cb => {
        cb.checked = workDaysArray.includes(cb.value);
    });

    const modal = new bootstrap.Modal(document.getElementById("scheduleModal"));
    modal.show();
};




            // ✅ Improved validation
            const scheduleForm = document.getElementById('scheduleForm');
            const workDaysError = document.getElementById('workDaysError');
            const workDaysCheckboxes = document.querySelectorAll('.checkbox-group input[type="checkbox"]');

            function validateWorkDays() {
                const checked = document.querySelectorAll('.checkbox-group input[type="checkbox"]:checked');
                if (checked.length === 0) {
                    workDaysError.classList.remove('d-none');
                    return false;
                } else {
                    workDaysError.classList.add('d-none');
                    return true;
                }
            }

            scheduleForm.addEventListener('submit', function(e) {
                if (!validateWorkDays()) e.preventDefault();
            });

            workDaysCheckboxes.forEach(cb => cb.addEventListener('change', validateWorkDays));

            // Init
            updateTable();
        });
   
