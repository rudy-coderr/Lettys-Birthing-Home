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
        supplies: {
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
            document.querySelectorAll("#suppliesTable tbody tr:not(.no-results)")
        );
        return rows.filter((row) => {
            const itemName =
                row.querySelector(".item-name")?.textContent.toLowerCase() || "";
            const category =
                row.querySelector("td:nth-child(2)")?.textContent.toLowerCase() ||
                "";
            const quantity =
                row.querySelector(".quantity-info")?.textContent.toLowerCase() ||
                "";
            const unit =
                row.querySelector("td:nth-child(4)")?.textContent.toLowerCase() ||
                "";
            const status = row.dataset.status?.toLowerCase() || "";

            const matchesSearch =
                state.supplies.searchQuery === "" ||
                itemName.includes(state.supplies.searchQuery) ||
                category.includes(state.supplies.searchQuery) ||
                quantity.includes(state.supplies.searchQuery) ||
                unit.includes(state.supplies.searchQuery);

            const matchesStatus =
                state.supplies.statusFilter === "all" ||
                status === state.supplies.statusFilter;

            return matchesSearch && matchesStatus;
        });
    }

    function updateTable() {
        const rows = Array.from(
            document.querySelectorAll("#suppliesTable tbody tr:not(.no-results)")
        );
        const visibleRows = getVisibleRows();
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / state.supplies.itemsPerPage);

        const start = (state.supplies.currentPage - 1) * state.supplies.itemsPerPage;
        const end = start + state.supplies.itemsPerPage;

        rows.forEach((row) => (row.style.display = "none"));
        visibleRows.slice(start, end).forEach((row) => (row.style.display = ""));

        // No results
        document.getElementById("noResultsSupplies").style.display =
            totalRows === 0 ? "block" : "none";

        // Pagination
        document.getElementById("suppliesPrevPage").disabled =
            state.supplies.currentPage === 1;
        document.getElementById("suppliesNextPage").disabled =
            state.supplies.currentPage === totalPages || totalPages === 0;
        document.getElementById("suppliesPageNumbers").textContent =
            totalPages > 0
                ? `Page ${state.supplies.currentPage} of ${totalPages}`
                : "Page 0 of 0";

        // Filter badge count
        const statusCount = document.getElementById("filterCountSuppliesStatus");
        statusCount.textContent =
            state.supplies.statusFilter !== "all" ? totalRows : "0";
        statusCount.style.display =
            state.supplies.statusFilter !== "all" ? "inline" : "none";

        // Clear filters button
        document.getElementById("clearFiltersBtnSupplies").style.display =
            state.supplies.statusFilter !== "all" || state.supplies.searchQuery
                ? "block"
                : "none";
    }

    // -----------------------------
    // SEARCH / FILTER
    // -----------------------------
    window.searchItems = function () {
        state.supplies.searchQuery =
            document.getElementById("searchInputSupplies").value.toLowerCase();
        state.supplies.currentPage = 1;
        updateTable();
    };

    window.filterItems = function (category, value) {
        if (category === "status")
            state.supplies.statusFilter = value.toLowerCase();
        state.supplies.currentPage = 1;

        const dropdown = document.getElementById("filterDropdownSuppliesStatus");
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
        state.supplies.searchQuery = "";
        state.supplies.statusFilter = "all";
        document.getElementById("searchInputSupplies").value = "";

        const options = document.querySelectorAll(
            "#filterDropdownSuppliesStatus .filter-option"
        );
        options.forEach((opt) => opt.classList.remove("selected"));
        options[0].classList.add("selected");

        state.supplies.currentPage = 1;
        updateTable();
    };

    // -----------------------------
    // PAGINATION
    // -----------------------------
    window.updateItemsPerPage = function () {
        state.supplies.itemsPerPage = parseInt(
            document.getElementById("suppliesItemsPerPage").value
        );
        state.supplies.currentPage = 1;
        updateTable();
    };

    window.changePage = function (direction) {
        const totalRows = getVisibleRows().length;
        const totalPages = Math.ceil(totalRows / state.supplies.itemsPerPage);
        state.supplies.currentPage = Math.min(
            Math.max(1, state.supplies.currentPage + direction),
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
