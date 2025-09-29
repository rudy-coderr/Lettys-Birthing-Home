document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");
    const form = document.getElementById("addSupplyForm");

    // 📱 Open sidebar
    btnOpen?.addEventListener("click", function () {
        sidebar.classList.add("mobile-show");
        sidebarOverlay.classList.add("show");
    });

    // 📱 Close sidebar
    btnClose?.addEventListener("click", function () {
        sidebar.classList.remove("mobile-show");
        sidebarOverlay.classList.remove("show");
    });

    // 📱 Close when clicking overlay
    sidebarOverlay?.addEventListener("click", function () {
        sidebar.classList.remove("mobile-show");
        this.classList.remove("show");
    });

    // 📝 Form validation
    form?.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });

    // 🖼️ Image upload handling
    window.handleImageUploadClick = function () {
        document.getElementById("supplyImage").click();
    };

    window.previewImage = function (input, previewId) {
        const preview = document.getElementById(previewId);
        const actions = document.getElementById("supplyImageActions");
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";
                actions.style.display = "block";
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.removeImage = function (inputId, previewId, actionsId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const actions = document.getElementById(actionsId);
        input.value = "";
        preview.style.display = "none";
        actions.style.display = "none";
    };

    // 🔽 Dropdown toggle with rotate
    window.toggleDropdown = function (element) {
        const icon = element.querySelector(".dropdown-icon");
        icon?.classList.toggle("rotate");
    };
});
