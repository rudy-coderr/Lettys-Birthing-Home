document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editSupplyForm");
    form.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });

    // 📱 Sidebar mobile toggle
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    btnOpen?.addEventListener("click", function () {
        sidebar.classList.add("mobile-show");
        sidebarOverlay.classList.add("show");
    });

    btnClose?.addEventListener("click", function () {
        sidebar.classList.remove("mobile-show");
        sidebarOverlay.classList.remove("show");
    });

    sidebarOverlay?.addEventListener("click", function () {
        sidebar.classList.remove("mobile-show");
        this.classList.remove("show");
    });

    // 🔽 Dropdown toggle in sidebar
    window.toggleDropdown = function (element) {
        const icon = element.querySelector(".dropdown-icon");
        icon.classList.toggle("rotate");
    };

    // 📌 Filter dropdown toggle
    window.toggleFilter = function (id) {
        document.getElementById(id).classList.toggle("show");
    };

    // Close filter dropdowns when clicking outside
    document.addEventListener("click", function (event) {
        const filterDropdowns = document.querySelectorAll(".filter-dropdown-menu");
        const filterButtons = document.querySelectorAll(".filter-btn");

        if (
            !Array.from(filterButtons).some(btn => btn.contains(event.target)) &&
            !Array.from(filterDropdowns).some(dropdown => dropdown.contains(event.target))
        ) {
            filterDropdowns.forEach(dropdown => dropdown.classList.remove("show"));
        }
    });
});

function toggleEditMode() {
    const editBtnText = document.getElementById("editBtnText");
    const editModeAlert = document.getElementById("editModeAlert");
    const formActions = document.getElementById("formActions");
    const inputs = document.querySelectorAll("#editSupplyForm input, #editSupplyForm select, #editSupplyForm textarea");

    if (editBtnText.textContent === "Edit") {
        editBtnText.textContent = "Cancel";
        editModeAlert.style.display = "block";
        formActions.style.display = "block";
        inputs.forEach(input => {
            if (input.tagName.toLowerCase() === "select") {
                input.removeAttribute("disabled");
            } else {
                input.removeAttribute("readonly");
            }
        });
    } else {
        editBtnText.textContent = "Edit";
        editModeAlert.style.display = "none";
        formActions.style.display = "none";
        inputs.forEach(input => {
            if (input.tagName.toLowerCase() === "select") {
                input.setAttribute("disabled", true);
            } else {
                input.setAttribute("readonly", true);
            }
        });
        document.getElementById("editSupplyForm").reset();
    }
}
