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
        medicines: {
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
            document.querySelectorAll("#medicinesTable tbody tr:not(.no-results)")
        );
        return rows.filter((row) => {
            const itemName =
                row.querySelector(".item-name")?.textContent.toLowerCase() || "";
            const category =
                row.querySelector("td:nth-child(2)")?.textContent.toLowerCase() || "";
            const quantity =
                row.querySelector(".quantity-info")?.textContent.toLowerCase() || "";
            const unit =
                row.querySelector("td:nth-child(4)")?.textContent.toLowerCase() || "";
            const status = row.dataset.status?.toLowerCase() || "";

            const matchesSearch =
                state.medicines.searchQuery === "" ||
                itemName.includes(state.medicines.searchQuery) ||
                category.includes(state.medicines.searchQuery) ||
                quantity.includes(state.medicines.searchQuery) ||
                unit.includes(state.medicines.searchQuery);

            const matchesStatus =
                state.medicines.statusFilter === "all" ||
                status === state.medicines.statusFilter;

            return matchesSearch && matchesStatus;
        });
    }

    function updateTable() {
        const rows = Array.from(
            document.querySelectorAll("#medicinesTable tbody tr:not(.no-results)")
        );
        const visibleRows = getVisibleRows();
        const totalRows = visibleRows.length;
        const totalPages = Math.ceil(totalRows / state.medicines.itemsPerPage);

        const start = (state.medicines.currentPage - 1) * state.medicines.itemsPerPage;
        const end = start + state.medicines.itemsPerPage;

        rows.forEach((row) => (row.style.display = "none"));
        visibleRows.slice(start, end).forEach((row) => (row.style.display = ""));

        // No results
        document.getElementById("noResultsMedicines").style.display =
            totalRows === 0 ? "block" : "none";

        // Pagination
        document.getElementById("medicinesPrevPage").disabled =
            state.medicines.currentPage === 1;
        document.getElementById("medicinesNextPage").disabled =
            state.medicines.currentPage === totalPages || totalPages === 0;
        document.getElementById("medicinesPageNumbers").textContent =
            totalPages > 0
                ? `Page ${state.medicines.currentPage} of ${totalPages}`
                : "Page 0 of 0";

        // Filter badge count
        const statusCount = document.getElementById("filterCountMedicinesStatus");
        statusCount.textContent =
            state.medicines.statusFilter !== "all" ? totalRows : "0";
        statusCount.style.display =
            state.medicines.statusFilter !== "all" ? "inline" : "none";

        // Clear filters button
        document.getElementById("clearFiltersBtnMedicines").style.display =
            state.medicines.statusFilter !== "all" || state.medicines.searchQuery
                ? "block"
                : "none";
    }

    // -----------------------------
    // SEARCH / FILTER
    // -----------------------------
    window.searchItems = function () {
        state.medicines.searchQuery =
            document.getElementById("searchInputMedicines").value.toLowerCase();
        state.medicines.currentPage = 1;
        updateTable();
    };

    window.filterItems = function (category, value) {
        if (category === "status")
            state.medicines.statusFilter = value.toLowerCase();
        state.medicines.currentPage = 1;

        const dropdown = document.getElementById("filterDropdownMedicinesStatus");
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
        state.medicines.searchQuery = "";
        state.medicines.statusFilter = "all";
        document.getElementById("searchInputMedicines").value = "";

        const options = document.querySelectorAll(
            "#filterDropdownMedicinesStatus .filter-option"
        );
        options.forEach((opt) => opt.classList.remove("selected"));
        options[0].classList.add("selected");

        state.medicines.currentPage = 1;
        updateTable();
    };

    // -----------------------------
    // PAGINATION
    // -----------------------------
    window.updateItemsPerPage = function () {
        state.medicines.itemsPerPage = parseInt(
            document.getElementById("medicinesItemsPerPage").value
        );
        state.medicines.currentPage = 1;
        updateTable();
    };

    window.changePage = function (direction) {
        const totalRows = getVisibleRows().length;
        const totalPages = Math.ceil(totalRows / state.medicines.itemsPerPage);
        state.medicines.currentPage = Math.min(
            Math.max(1, state.medicines.currentPage + direction),
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
