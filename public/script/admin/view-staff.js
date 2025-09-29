
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");

            // Open sidebar
            btnOpen.addEventListener("click", function() {
                sidebar.classList.add("mobile-show");
                sidebarOverlay.classList.add("show");
            });

            // Close sidebar
            btnClose.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                sidebarOverlay.classList.remove("show");
            });

            // Close when clicking overlay
            sidebarOverlay.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                this.classList.remove("show");
            });

            // Form validation
            const form = document.getElementById("editStaffForm");
            form.addEventListener("submit", function(event) {
                let valid = true;
                const workDays = form.querySelectorAll('input[name="workDays[]"]:checked');
                const workDaysError = document.getElementById("workDaysError");

                if (workDays.length === 0) {
                    valid = false;
                    workDaysError.style.display = "block";
                } else {
                    workDaysError.style.display = "none";
                }

                if (!form.checkValidity() || !valid) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            });
        });

        function toggleDropdown(element) {
            const icon = element.querySelector('.dropdown-icon');
            icon.classList.toggle('rotate');
        }

        function toggleEditMode() {
            const editBtnText = document.getElementById("editBtnText");
            const editModeAlert = document.getElementById("editModeAlert");
            const formActions = document.getElementById("formActions");
            const inputs = document.querySelectorAll(
                "#editStaffForm input:not([name='staff_id']), #editStaffForm select, #editStaffForm textarea");
            const checkboxes = document.querySelectorAll(".readonlyMode");

            if (editBtnText.textContent === "Edit") {
                editBtnText.textContent = "Cancel";
                editModeAlert.style.display = "block";
                formActions.style.display = "block";
                inputs.forEach(input => input.removeAttribute("readonly"));
                inputs.forEach(input => input.removeAttribute("disabled"));
                checkboxes.forEach(checkbox => checkbox.removeAttribute("disabled"));
            } else {
                editBtnText.textContent = "Edit";
                editModeAlert.style.display = "none";
                formActions.style.display = "none";
                inputs.forEach(input => input.setAttribute("readonly", true));
                inputs.forEach(input => input.setAttribute("disabled", true));
                checkboxes.forEach(checkbox => checkbox.setAttribute("disabled", true));
                document.getElementById("editStaffForm").classList.remove("was-validated");
                document.getElementById("workDaysError").style.display = "none";
            }
        }
