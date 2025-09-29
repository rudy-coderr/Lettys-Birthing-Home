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

    // 🔎 Search & Filters
    let currentPage = 1;
    let itemsPerPage = parseInt(document.getElementById("visitsItemsPerPage").value);
    let searchQuery = "";
    let dateFilter = "all";
    let recordTypeFilter = "all";

    function getVisibleRows() {
        const rows = Array.from(document.querySelectorAll("#visitsTable tbody tr:not(.no-results)"));
        return rows.filter((row) => {
            const patientId = row.querySelector(".patient-id").textContent.toLowerCase();
            const patientName = row.querySelector(".patient-name").textContent.toLowerCase();
            const fileName = row.querySelector(".pdf-file-name").textContent.toLowerCase();
            const generatedBy = row.querySelector(".generated-by").textContent.toLowerCase();

            const rowType = row.getAttribute("data-type"); // prenatal, intrapartum, etc.
            const rowYear = row.getAttribute("data-date"); // YYYY

            // Search filter
            const matchesSearch =
                searchQuery === "" ||
                patientId.includes(searchQuery) ||
                patientName.includes(searchQuery) ||
                fileName.includes(searchQuery) ||
                generatedBy.includes(searchQuery);

            // Record type filter
            const matchesType = recordTypeFilter === "all" || rowType === recordTypeFilter;

            // Date filter
            const matchesDate = dateFilter === "all" || rowYear === dateFilter;

            return matchesSearch && matchesType && matchesDate;
        });
    }

    function updateTable() {
        const rows = Array.from(document.querySelectorAll("#visitsTable tbody tr:not(.no-results)"));
        const visibleRows = getVisibleRows();
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / itemsPerPage);

        // Update Clear Filters visibility
        document.getElementById("clearFiltersBtnVisits").style.display =
            searchQuery || dateFilter !== "all" || recordTypeFilter !== "all" ? "block" : "none";

        // Paginate rows
        currentPage = Math.min(currentPage, Math.max(1, totalPages));
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        rows.forEach((row) => (row.style.display = "none"));
        visibleRows.slice(start, end).forEach((row) => (row.style.display = ""));

        // No results message
        document.getElementById("noResultsVisits").style.display = totalRows === 0 ? "block" : "none";

        // Pagination controls
        document.getElementById("visitsPrevPage").disabled = currentPage === 1;
        document.getElementById("visitsNextPage").disabled = currentPage === totalPages || totalPages === 0;

        const pageNumbers = document.getElementById("visitsPageNumbers");
        pageNumbers.textContent =
            totalPages > 0 ? `Page ${currentPage} of ${totalPages}` : "Page 0 of 0";
    }

    // 🔍 Search
    window.applyFilters = function () {
        searchQuery = document.getElementById("searchInputVisits").value.toLowerCase();
        currentPage = 1;
        updateTable();
    };

    // 📌 Record Type Filter
    window.setRecordType = function (type) {
        recordTypeFilter = type;
        document.getElementById("selectedRecordType").textContent =
            type === "all" ? "Record For" : type.charAt(0).toUpperCase() + type.slice(1);
        const options = document.querySelectorAll("#recordTypeFilter .filter-option");
        options.forEach((opt) => opt.classList.remove("selected"));
        event.target.classList.add("selected");
        document.getElementById("recordTypeFilter").classList.remove("show");
        currentPage = 1;
        updateTable();
    };

    // 📅 Date Filter
    window.setDateFilter = function (year) {
        dateFilter = year;
        const options = document.querySelectorAll("#dateFilterVisits .filter-option");
        options.forEach((opt) => opt.classList.remove("selected"));
        event.target.classList.add("selected");
        document.getElementById("dateFilterVisits").classList.remove("show");
        currentPage = 1;
        updateTable();
    };

    // ❌ Clear All Filters
    window.clearAllFilters = function () {
        searchQuery = "";
        dateFilter = "all";
        recordTypeFilter = "all";
        document.getElementById("searchInputVisits").value = "";
        document.getElementById("selectedRecordType").textContent = "Record For";

        document.querySelectorAll("#recordTypeFilter .filter-option").forEach((opt) => opt.classList.remove("selected"));
        document.querySelector("#recordTypeFilter .filter-option").classList.add("selected");

        document.querySelectorAll("#dateFilterVisits .filter-option").forEach((opt) => opt.classList.remove("selected"));
        document.querySelector("#dateFilterVisits .filter-option").classList.add("selected");

        currentPage = 1;
        updateTable();
    };

    // 📄 Items per page
    window.updateItemsPerPage = function (type) {
        if (type === "visits") {
            itemsPerPage = parseInt(document.getElementById("visitsItemsPerPage").value);
            currentPage = 1;
            updateTable();
        }
    };

    // ⏪⏩ Pagination
    window.changePage = function (type, direction) {
        if (type === "visits") {
            const totalRows = getVisibleRows().length;
            const totalPages = Math.ceil(totalRows / itemsPerPage);
            currentPage = Math.min(Math.max(1, currentPage + direction), totalPages);
            updateTable();
        }
    };

    // Init
    updateTable();
});
