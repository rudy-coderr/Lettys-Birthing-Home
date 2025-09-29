document.addEventListener("DOMContentLoaded", function () {
    // -----------------------------
    // MOBILE SIDEBAR
    // -----------------------------
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    if (btnOpen && btnClose && sidebar && sidebarOverlay) {
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
    }

    // -----------------------------
    // DROPDOWNS
    // -----------------------------
    window.toggleDropdown = function (element) {
        const icon = element.querySelector(".dropdown-icon");
        icon.classList.toggle("rotate");
    };

    window.toggleFilter = function (id) {
        document.getElementById(id).classList.toggle("show");
    };

    document.addEventListener("click", function (event) {
        const dropdowns = document.querySelectorAll(".filter-dropdown-menu");
        const buttons = document.querySelectorAll(".filter-btn");
        if (
            ![...buttons].some((btn) => btn.contains(event.target)) &&
            ![...dropdowns].some((drop) => drop.contains(event.target))
        ) {
            dropdowns.forEach((drop) => drop.classList.remove("show"));
        }
    });

    // -----------------------------
    // STATE MANAGEMENT
    // -----------------------------
    const state = {
        vaccines: {
            currentPage: 1,
            itemsPerPage: 10,
            statusFilter: "all",
            searchQuery: "",
        },
    };

    // -----------------------------
    // TABLE HELPERS
    // -----------------------------
    function getVisibleRows() {
        const rows = Array.from(
            document.querySelectorAll("#vaccinesTable tbody tr:not(.no-results)")
        );
        return rows.filter((row) => {
            const itemName =
                row.querySelector(".item-name")?.textContent.toLowerCase() || "";
            const batchNo =
                row.querySelector("td:nth-child(2)")?.textContent.toLowerCase() ||
                "";
            const quantity =
                row.querySelector(".quantity-info")?.textContent.toLowerCase() ||
                "";
            const unit =
                row.querySelector("td:nth-child(4)")?.textContent.toLowerCase() ||
                "";
            const expiry =
                row.querySelector("td:nth-child(5)")?.textContent.toLowerCase() ||
                "";
            const status = row.dataset.status?.toLowerCase() || "";

            const matchesSearch =
                state.vaccines.searchQuery === "" ||
                itemName.includes(state.vaccines.searchQuery) ||
                batchNo.includes(state.vaccines.searchQuery) ||
                quantity.includes(state.vaccines.searchQuery) ||
                unit.includes(state.vaccines.searchQuery) ||
                expiry.includes(state.vaccines.searchQuery);

            const matchesStatus =
                state.vaccines.statusFilter === "all" ||
                status === state.vaccines.statusFilter;

            return matchesSearch && matchesStatus;
        });
    }

    function updateTable() {
        const rows = Array.from(
            document.querySelectorAll("#vaccinesTable tbody tr:not(.no-results)")
        );
        const visibleRows = getVisibleRows();
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / state.vaccines.itemsPerPage);

        const start = (state.vaccines.currentPage - 1) * state.vaccines.itemsPerPage;
        const end = start + state.vaccines.itemsPerPage;

        rows.forEach((row) => (row.style.display = "none"));
        visibleRows.slice(start, end).forEach((row) => (row.style.display = ""));

        // No results
        document.getElementById("noResultsVaccines").style.display =
            totalRows === 0 ? "block" : "none";

        // Pagination
        document.getElementById("vaccinesPrevPage").disabled =
            state.vaccines.currentPage === 1;
        document.getElementById("vaccinesNextPage").disabled =
            state.vaccines.currentPage === totalPages || totalPages === 0;
        document.getElementById("vaccinesPageNumbers").textContent =
            totalPages > 0
                ? `Page ${state.vaccines.currentPage} of ${totalPages}`
                : "Page 0 of 0";

        // Filter badge count
        const statusCount = document.getElementById("filterCountVaccinesStatus");
        statusCount.textContent =
            state.vaccines.statusFilter !== "all" ? totalRows : "0";
        statusCount.style.display =
            state.vaccines.statusFilter !== "all" ? "inline" : "none";

        // Clear filters button
        document.getElementById("clearFiltersBtnVaccines").style.display =
            state.vaccines.statusFilter !== "all" || state.vaccines.searchQuery
                ? "block"
                : "none";
    }

    // -----------------------------
    // SEARCH / FILTER
    // -----------------------------
    window.searchItems = function () {
        state.vaccines.searchQuery =
            document.getElementById("searchInputVaccines").value.toLowerCase();
        state.vaccines.currentPage = 1;
        updateTable();
    };

    window.filterItems = function (category, value) {
        if (category === "status")
            state.vaccines.statusFilter = value.toLowerCase();
        state.vaccines.currentPage = 1;

        const dropdown = document.getElementById("filterDropdownVaccinesStatus");
        dropdown.querySelectorAll(".filter-option").forEach((opt) =>
            opt.classList.remove("selected")
        );
        const selected = Array.from(
            dropdown.querySelectorAll(".filter-option")
        ).find(
            (opt) =>
                opt.textContent.toLowerCase() === value.toLowerCase() ||
                value === "all"
        );
        if (selected) selected.classList.add("selected");

        updateTable();
        dropdown.classList.remove("show");
    };

    window.clearAllFilters = function () {
        state.vaccines.searchQuery = "";
        state.vaccines.statusFilter = "all";
        document.getElementById("searchInputVaccines").value = "";

        const options = document.querySelectorAll(
            "#filterDropdownVaccinesStatus .filter-option"
        );
        options.forEach((opt) => opt.classList.remove("selected"));
        options[0].classList.add("selected");

        state.vaccines.currentPage = 1;
        updateTable();
    };

    // -----------------------------
    // PAGINATION
    // -----------------------------
    window.updateItemsPerPage = function () {
        state.vaccines.itemsPerPage = parseInt(
            document.getElementById("vaccinesItemsPerPage").value
        );
        state.vaccines.currentPage = 1;
        updateTable();
    };

    window.changePage = function (direction) {
        const totalRows = getVisibleRows().length;
        const totalPages = Math.ceil(totalRows / state.vaccines.itemsPerPage);
        state.vaccines.currentPage = Math.min(
            Math.max(1, state.vaccines.currentPage + direction),
            totalPages
        );
        updateTable();
    };

    // -----------------------------
    // MODALS
    // -----------------------------
    window.openRestockModal = function (id, name, currentQuantity, restockUrl) {
        document.getElementById("restockItemId").value = id;
        document.getElementById("restockItemName").innerText = name;
        document.getElementById("restockCurrentQuantity").innerText =
            currentQuantity;
        document.getElementById("restockQuantity").value = "";
        document.getElementById("restockForm").action = restockUrl;

        new bootstrap.Modal(document.getElementById("restockModal")).show();
    };

    window.openDeleteModal = function (deleteUrl, itemName) {
        document.getElementById("deleteItemName").innerText = itemName;
        document.getElementById("deleteForm").action = deleteUrl;

        new bootstrap.Modal(document.getElementById("deleteModal")).show();
    };

    // -----------------------------
    // INITIALIZE
    // -----------------------------
    updateTable();
});
