document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    // Sidebar toggle
    btnOpen.addEventListener("click", function () {
        sidebar.classList.add("mobile-show");
        sidebarOverlay.classList.add("show");
    });

    btnClose.addEventListener("click", function () {
        sidebar.classList.remove("mobile-show");
        sidebarOverlay.classList.remove("show");
    });

    sidebarOverlay.addEventListener("click", function () {
        sidebar.classList.remove("mobile-show");
        this.classList.remove("show");
    });

    // Dropdown toggle
    window.toggleDropdown = function (element) {
        const icon = element.querySelector(".dropdown-icon");
        icon.classList.toggle("rotate");
    };

    // Filter dropdown toggle
    window.toggleFilter = function (id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle("show");
    };

    // Close filter dropdown when clicking outside
    document.addEventListener("click", function (event) {
        const filterDropdowns = document.querySelectorAll(".filter-dropdown-menu");
        const filterButtons = document.querySelectorAll(".filter-btn");
        if (
            !Array.from(filterButtons).some((btn) => btn.contains(event.target)) &&
            !Array.from(filterDropdowns).some((dropdown) => dropdown.contains(event.target))
        ) {
            filterDropdowns.forEach((dropdown) => dropdown.classList.remove("show"));
        }
    });

    // Search and filter functionality
    let currentPage = 1;
    let itemsPerPage = parseInt(document.getElementById("confirmedItemsPerPage").value);
    let statusFilter = "all";
    let searchQuery = "";

    function getVisibleRows() {
        const rows = Array.from(
            document.querySelectorAll("#confirmedAppointmentsTable tbody tr:not(.no-results)")
        );
        return rows.filter((row) => {
            const firstName = row.querySelector(".appointment-first-name").textContent.toLowerCase();
            const lastName = row.querySelector(".appointment-last-name").textContent.toLowerCase();
            const phone = row.querySelector(".appointment-phone").textContent.toLowerCase();
            const reason = row.querySelector(".appointment-reason").textContent.toLowerCase();
            const status = row.querySelector(".appointment-status").textContent.toLowerCase();

            // Search filter
            const matchesSearch =
                searchQuery === "" ||
                firstName.includes(searchQuery) ||
                lastName.includes(searchQuery) ||
                phone.includes(searchQuery) ||
                reason.includes(searchQuery);

            // Status filter
            const matchesStatus = statusFilter === "all" || status === statusFilter;

            return matchesSearch && matchesStatus;
        });
    }

    function updateTable() {
        const rows = Array.from(
            document.querySelectorAll("#confirmedAppointmentsTable tbody tr:not(.no-results)")
        );
        const visibleRows = getVisibleRows();
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);

        // Update filter count
        const filterCount = document.getElementById("statusFilterCountConfirmed");
        filterCount.textContent = statusFilter !== "all" ? totalRows : "0";
        filterCount.style.display = statusFilter !== "all" ? "inline" : "none";
        document.getElementById("clearFiltersBtnConfirmed").style.display =
            statusFilter !== "all" || searchQuery ? "block" : "none";

        // Update pagination
        currentPage = Math.min(currentPage, Math.max(1, totalPages));
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Show/hide rows
        rows.forEach((row) => (row.style.display = "none"));
        visibleRows.slice(start, end).forEach((row) => (row.style.display = ""));

        // Update no results message
        const noResults = document.getElementById("noResultsConfirmed");
        const hasAppointments =
            document.querySelectorAll("#confirmedAppointmentsTable tbody tr:not(.no-results)").length >
            0;
        const isFiltered = searchQuery !== "" || statusFilter !== "all";

        noResults.style.display =
            hasAppointments && totalRows === 0 && isFiltered ? "block" : "none";

        // Update pagination controls
        const prevBtn = document.getElementById("confirmedPrevPage");
        const nextBtn = document.getElementById("confirmedNextPage");
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;

        const pageNumbers = document.getElementById("confirmedPageNumbers");
        pageNumbers.textContent =
            totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : "Page 0 of 0";
    }

    window.searchAppointments = function (type) {
        if (type === "confirmed") {
            searchQuery = document.getElementById("searchInputConfirmed").value.toLowerCase();
            currentPage = 1;
            updateTable();
        }
    };

    window.filterAppointments = function (category, value, type) {
        if (type === "confirmed" && category === "status") {
            statusFilter = value;
            const options = document.querySelectorAll(
                "#statusFilterConfirmed .filter-option"
            );
            options.forEach((opt) => opt.classList.remove("selected"));
            event.target.classList.add("selected");
            document.getElementById("statusFilterConfirmed").classList.remove("show");
            currentPage = 1;
            updateTable();
        }
    };

    window.clearAllFilters = function (type) {
        if (type === "confirmed") {
            searchQuery = "";
            statusFilter = "all";
            document.getElementById("searchInputConfirmed").value = "";
            const options = document.querySelectorAll(
                "#statusFilterConfirmed .filter-option"
            );
            options.forEach((opt) => opt.classList.remove("selected"));
            options[0].classList.add("selected");
            currentPage = 1;
            updateTable();
        }
    };

    window.updateItemsPerPage = function (type) {
        if (type === "confirmed") {
            itemsPerPage = parseInt(document.getElementById("confirmedItemsPerPage").value);
            currentPage = 1;
            updateTable();
        }
    };

    window.changePage = function (type, direction) {
        if (type === "confirmed") {
            const totalRows = getVisibleRows().length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
            updateTable();
        }
    };

    // Action button functions
    window.editAppointment = function (id) {
        window.location.href = `{{ url('appointments') }}/${id}/edit`;
    };

    window.openRescheduleModal = function (button) {
        const id = button.getAttribute("data-id");
        const date = button.getAttribute("data-date");
        const time = button.getAttribute("data-time");
        document.getElementById("rescheduleAppointmentId").value = id;
        document.getElementById("rescheduleDate").value = date;
        document.getElementById("rescheduleTime").value = time;
        const modal = new bootstrap.Modal(
            document.getElementById("rescheduleAppointmentModal")
        );
        modal.show();
    };

    window.openCancelModal = function (id) {
        document.getElementById("cancelAppointmentId").value = id;
        const modal = new bootstrap.Modal(
            document.getElementById("cancelAppointmentModal")
        );
        modal.show();
    };

    window.openDeleteModal = function (id) {
        document.getElementById("deleteAppointmentId").value = id;
        const modal = new bootstrap.Modal(
            document.getElementById("deleteAppointmentModal")
        );
        modal.show();
    };

    window.sendMessage = function (phone) {
        if (phone === "N/A") {
            Swal.fire({
                icon: "error",
                title: "No Phone Number",
                text: "No phone number available for this client.",
                confirmButtonText: "OK",
            });
            return;
        }
        document.getElementById("messagePhone").value = phone;
        const modal = new bootstrap.Modal(
            document.getElementById("sendMessageModal")
        );
        modal.show();
    };

    // Initialize table
    updateTable();
});
