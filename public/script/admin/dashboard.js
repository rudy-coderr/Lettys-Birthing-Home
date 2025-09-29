        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");
            const calendarIframeContainer = document.getElementById("calendarIframeContainer");
            const calendarIframe = document.getElementById("calendarIframe");
            const refreshCalendar = document.getElementById("refreshCalendar");
            const branchSelect = document.getElementById("branchSelect");
            const branchDropdownMenu = document.getElementById("branchDropdownMenu");
            const calendarBranch = document.getElementById("calendarBranch");
            const branchForm = document.getElementById("branchForm");
            const branchInput = document.getElementById("branchInput");

            // Sidebar functionality
            btnOpen.addEventListener("click", function() {
                sidebar.classList.add("mobile-show");
                sidebarOverlay.classList.add("show");
            });

            btnClose.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                sidebarOverlay.classList.remove("show");
            });

            sidebarOverlay.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                this.classList.remove("show");
            });

            // Calendar refresh functionality
            if (refreshCalendar) {
                refreshCalendar.addEventListener("click", function() {
                    calendarIframeContainer.classList.add("loading");
                    calendarIframe.src = calendarIframe.src;
                    calendarIframe.onload = function() {
                        calendarIframeContainer.classList.remove("loading");
                    };
                });
            }

            // Calendar links for each branch
            const calendarLinks = {
                'Combined': 'https://calendar.google.com/calendar/embed?src=c_6fdad041de148ea644015805de2306177fe1d3be73868f6ed5fbdbfc21a70873%40group.calendar.google.com&src=c_aeec3ea37d713805c9b9c73f1d5c2720bc63e192cd70194c8300ca1044e2df5c%40group.calendar.google.com&ctz=Asia%2FManila&hl=en&color=%234CAF50&color=%234197D2&showTitle=0&showNav=0&showDate=0&showPrint=0&showTabs=0&showCalendars=0&showTz=0',
                'Santa Justina': 'https://calendar.google.com/calendar/embed?src=c_6fdad041de148ea644015805de2306177fe1d3be73868f6ed5fbdbfc21a70873%40group.calendar.google.com&ctz=Asia%2FManila&hl=en&color=%234CAF50&showTitle=0&showNav=0&showDate=0&showPrint=0&showTabs=0&showCalendars=0&showTz=0',
                'San Pedro': 'https://calendar.google.com/calendar/embed?src=c_aeec3ea37d713805c9b9c73f1d5c2720bc63e192cd70194c8300ca1044e2df5c%40group.calendar.google.com&ctz=Asia%2FManila&hl=en&color=%234197D2&showTitle=0&showNav=0&showDate=0&showPrint=0&showTabs=0&showCalendars=0&showTz=0'
            };

            // Branch dropdown toggle
            branchSelect.addEventListener("click", function() {
                branchDropdownMenu.classList.toggle("show");
                branchSelect.setAttribute("aria-expanded", branchDropdownMenu.classList.contains("show"));
            });

            // Branch dropdown option click
            branchDropdownMenu.addEventListener("click", function(e) {
                if (e.target.classList.contains("filter-option")) {
                    const selectedBranch = e.target.getAttribute("data-value");

                    // Update dropdown text
                    branchSelect.querySelector(".selected-option").textContent = selectedBranch;
                    calendarBranch.textContent = selectedBranch === 'Combined' ? 'All Branches' :
                        selectedBranch;

                    // Update calendar iframe
                    calendarIframeContainer.classList.add("loading");
                    calendarIframe.src = calendarLinks[selectedBranch];
                    calendarIframe.onload = function() {
                        calendarIframeContainer.classList.remove("loading");
                    };

                    // Close dropdown
                    branchDropdownMenu.classList.remove("show");
                    branchSelect.setAttribute("aria-expanded", "false");

                    // Update hidden input & submit form
                    branchInput.value = selectedBranch;
                    branchForm.submit();
                }
            });

             window.toggleDropdown = function(element) {
                const icon = element.querySelector('.dropdown-icon');
                icon.classList.toggle('rotate');
            };

            // Close dropdown when clicking outside
            document.addEventListener("click", function(e) {
                if (!branchSelect.contains(e.target) && !branchDropdownMenu.contains(e.target)) {
                    branchDropdownMenu.classList.remove("show");
                    branchSelect.setAttribute("aria-expanded", "false");
                }
            });
        });
