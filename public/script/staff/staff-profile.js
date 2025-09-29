
        document.addEventListener("DOMContentLoaded", function() {
            // -----------------------------
            // SIDEBAR
            // -----------------------------
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");

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

            // -----------------------------
            // FORM VALIDATION
            // -----------------------------
            const profileForm = document.getElementById("profileForm");
            profileForm.addEventListener("submit", function(event) {
                if (!profileForm.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                profileForm.classList.add("was-validated");
            });

            const changePasswordForm = document.getElementById("changePasswordForm");
            if (changePasswordForm) {
                changePasswordForm.addEventListener("submit", function(event) {
                    const newPassword = document.getElementById("newPassword").value;
                    const confirmPassword = document.getElementById("newPasswordConfirmation").value;
                    const passwordLengthError = document.getElementById("passwordLengthError");
                    const passwordMismatch = document.getElementById("passwordMismatch");

                    if (!changePasswordForm.checkValidity() || newPassword.length < 8 || newPassword !==
                        confirmPassword) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    passwordLengthError.style.display = newPassword.length < 8 ? "block" : "none";
                    passwordMismatch.style.display = newPassword !== confirmPassword ? "block" : "none";

                    changePasswordForm.classList.add("was-validated");
                });
            }

            // -----------------------------
            // AVATAR DRAG & DROP
            // -----------------------------
            const imageUploadSection = document.getElementById("imageUploadSection");
            if (imageUploadSection) {
                imageUploadSection.addEventListener("dragover", e => {
                    e.preventDefault();
                    e.currentTarget.querySelector(".border").classList.add("border-primary");
                });
                imageUploadSection.addEventListener("dragleave", e => {
                    e.currentTarget.querySelector(".border").classList.remove("border-primary");
                });
                imageUploadSection.addEventListener("drop", e => {
                    e.preventDefault();
                    const border = e.currentTarget.querySelector(".border");
                    border.classList.remove("border-primary");
                    const fileInput = document.getElementById("avatarInput");
                    fileInput.files = e.dataTransfer.files;
                    previewImage(fileInput, "avatarPreview");
                });
            }
        });

        // -----------------------------
        // TOGGLE EDIT MODE
        // -----------------------------
        function toggleEditMode() {
            const editBtnText = document.getElementById("editBtnText");
            const formActions = document.getElementById("formActions");
            const editModeAlert = document.getElementById("editModeAlert");
            const avatarUploadBtn = document.getElementById("avatarUploadBtn");
            const inputs = document.querySelectorAll(
                "#profileForm input:not([name='staff_id']):not([type='file'])"
            );
            const selects = document.querySelectorAll("#profileForm select");
            const textareas = document.querySelectorAll("#profileForm textarea");

            const isEditing = editBtnText.textContent === "Edit Profile";

            editBtnText.textContent = isEditing ? "Cancel" : "Edit Profile";
            formActions.style.display = isEditing ? "block" : "none";
            editModeAlert.style.display = isEditing ? "block" : "none";
            avatarUploadBtn.style.display = isEditing ? "flex" : "none";

            inputs.forEach(input => isEditing ? input.removeAttribute("readonly") : input.setAttribute("readonly", true));
            selects.forEach(select => isEditing ? select.removeAttribute("disabled") : select.setAttribute("disabled",
                true));
            textareas.forEach(textarea => isEditing ? textarea.removeAttribute("readonly") : textarea.setAttribute(
                "readonly", true));
        }


        // -----------------------------
        // AVATAR PREVIEW & UPLOAD
        // -----------------------------
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const actions = document.getElementById("avatarActions");
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                    if (actions) actions.style.display = "block";
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeNewImage() {
            const preview = document.getElementById("avatarPreview");
            const actions = document.getElementById("avatarActions");
            const fileInput = document.getElementById("avatarInput");
            if (preview) preview.style.display = "none";
            if (actions) actions.style.display = "none";
            if (fileInput) fileInput.value = "";
        }

        function uploadAvatar() {
            document.getElementById("avatarInputHidden").click();
        }

        function submitAvatarForm() {
            document.getElementById("avatarForm").submit();
        }
        // -----------------------------
        // PASSWORD VISIBILITY TOGGLE
        // -----------------------------
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
  