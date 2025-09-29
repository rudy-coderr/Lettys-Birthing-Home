
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
            const form = document.getElementById("addStaffForm");
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

            // Reset form
            form.addEventListener("reset", function() {
                form.classList.remove("was-validated");
                document.getElementById("workDaysError").style.display = "none";
            });
        });

        function toggleDropdown(element) {
            const icon = element.querySelector('.dropdown-icon');
            icon.classList.toggle('rotate');
        }
  