document.addEventListener("DOMContentLoaded", function () {
    // -----------------------------
    // SIDEBAR TOGGLE
    // -----------------------------
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    if (btnOpen && btnClose && sidebar && sidebarOverlay) {
        btnOpen.addEventListener("click", () => {
            sidebar.classList.add("mobile-show");
            sidebarOverlay.classList.add("show");
        });

        btnClose.addEventListener("click", () => {
            sidebar.classList.remove("mobile-show");
            sidebarOverlay.classList.remove("show");
        });

        sidebarOverlay.addEventListener("click", () => {
            sidebar.classList.remove("mobile-show");
            sidebarOverlay.classList.remove("show");
        });
    }

    // -----------------------------
    // PROFILE FORM VALIDATION
    // -----------------------------
    const profileForm = document.getElementById("profileForm");
    if (profileForm) {
        profileForm.addEventListener("submit", function (e) {
            if (!profileForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            profileForm.classList.add("was-validated");
        });
    }

    // -----------------------------
    // CHANGE PASSWORD FORM VALIDATION
    // -----------------------------
    const changePasswordForm = document.getElementById("changePasswordForm");
    if (changePasswordForm) {
        changePasswordForm.addEventListener("submit", function (e) {
            if (!changePasswordForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("newPasswordConfirmation").value;
            const passwordLengthError = document.getElementById("passwordLengthError");
            const passwordMismatch = document.getElementById("passwordMismatch");

            passwordLengthError.style.display = newPassword.length < 8 ? "block" : "none";
            passwordMismatch.style.display = newPassword !== confirmPassword ? "block" : "none";

            if (newPassword.length < 8 || newPassword !== confirmPassword) {
                e.preventDefault();
                e.stopPropagation();
            }

            changePasswordForm.classList.add("was-validated");
        });
    }

    // -----------------------------
    // TOASTS
    // -----------------------------
    const toasts = document.querySelectorAll(".toast");
    toasts.forEach(toast => new bootstrap.Toast(toast).show());

    // -----------------------------
    // DRAG & DROP IMAGE UPLOAD (optional)
    // -----------------------------
    const imageUploadSection = document.getElementById("imageUploadSection");
    if (imageUploadSection) {
        imageUploadSection.addEventListener("dragover", function (e) {
            e.preventDefault();
            this.querySelector(".border")?.classList.add("border-primary");
        });

        imageUploadSection.addEventListener("dragleave", function () {
            this.querySelector(".border")?.classList.remove("border-primary");
        });

        imageUploadSection.addEventListener("drop", function (e) {
            e.preventDefault();
            this.querySelector(".border")?.classList.remove("border-primary");
            const fileInput = document.getElementById("avatarInputHidden");
            if (fileInput) {
                fileInput.files = e.dataTransfer.files;
                previewImage(fileInput);
            }
        });
    }
});

// -----------------------------
// EDIT MODE TOGGLE
// -----------------------------
function toggleEditMode() {
    const editBtnText = document.getElementById("editBtnText");
    const editModeAlert = document.getElementById("editModeAlert");
    const formActions = document.getElementById("formActions");
    const avatarUploadBtn = document.getElementById("avatarUploadBtn");
    const inputs = document.querySelectorAll("#profileForm input:not([name='avatar'])");
    const fileInput = document.getElementById("avatarInputHidden");
    const avatarPreview = document.getElementById("avatarPreview");
    const avatarActions = document.getElementById("avatarActions");

    if (editBtnText.textContent === "Edit Profile") {
        editBtnText.textContent = "Cancel";
        editModeAlert.style.display = "block";
        formActions.style.display = "flex";
        avatarUploadBtn.style.display = "flex";
        inputs.forEach(input => input.removeAttribute("readonly"));
        fileInput.removeAttribute("disabled");
    } else {
        editBtnText.textContent = "Edit Profile";
        editModeAlert.style.display = "none";
        formActions.style.display = "none";
        avatarUploadBtn.style.display = "none";
        inputs.forEach(input => input.setAttribute("readonly", true));
        fileInput.setAttribute("disabled", true);
        fileInput.value = "";
        if (avatarPreview) avatarPreview.style.display = "none";
        if (avatarActions) avatarActions.style.display = "none";
        profileForm.classList.remove("was-validated");
    }
}

// -----------------------------
// AVATAR FUNCTIONS
// -----------------------------
function uploadAvatar() {
    document.getElementById("avatarInputHidden")?.click();
}

function previewImage(input) {
    const preview = document.getElementById("avatarPreview");
    const actions = document.getElementById("avatarActions");
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = "block";
            }
            if (actions) actions.style.display = "flex";
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeNewImage() {
    const preview = document.getElementById("avatarPreview");
    const actions = document.getElementById("avatarActions");
    const fileInput = document.getElementById("avatarInputHidden");
    if (preview) preview.style.display = "none";
    if (actions) actions.style.display = "none";
    if (fileInput) fileInput.value = "";
}

function submitAvatarForm() {
    document.getElementById("avatarForm")?.submit();
}

function cancelEdit() {
    toggleEditMode();
}

function toggleDropdown(element) {
    const icon = element.querySelector(".dropdown-icon");
    icon?.classList.toggle("rotate");
}

// PASSWORD VISIBILITY TOGGLE
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
