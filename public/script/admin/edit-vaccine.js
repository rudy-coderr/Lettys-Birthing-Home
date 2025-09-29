document.addEventListener("DOMContentLoaded", function () {
    // 📱 Sidebar toggle (mobile)
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

    // 🔽 Dropdown toggle with rotate
    window.toggleDropdown = function (element) {
        const icon = element.querySelector(".dropdown-icon");
        icon?.classList.toggle("rotate");
    };

    // 📝 Form validation
    const form = document.getElementById("editVaccineForm");
    form?.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });
});

// ✏️ Toggle Edit Mode
function toggleEditMode() {
    const editBtnText = document.getElementById("editBtnText");
    const editModeAlert = document.getElementById("editModeAlert");
    const formActions = document.getElementById("formActions");

    // Select all inputs, selects, and textareas inside the form
    const inputs = document.querySelectorAll(
        "#editVaccineForm input, #editVaccineForm select, #editVaccineForm textarea"
    );

    if (editBtnText.textContent === "Edit") {
        editBtnText.textContent = "Cancel";
        editModeAlert.style.display = "block";
        formActions.style.display = "block";
        inputs.forEach((input) => {
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
        inputs.forEach((input) => {
            if (input.tagName.toLowerCase() === "select") {
                input.setAttribute("disabled", true);
            } else {
                input.setAttribute("readonly", true);
            }
        });

        // Reset form to original values when cancelling
        const form = document.getElementById("editVaccineForm");
        form.reset();
    }
}
