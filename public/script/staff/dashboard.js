
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");
            const calendarIframeContainer = document.getElementById("calendarIframeContainer");
            const calendarIframe = document.getElementById("calendarIframe");
            const refreshCalendar = document.getElementById("refreshCalendar");

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
                    calendarIframe.src = calendarIframe.src; // Reload iframe
                    calendarIframe.onload = function() {
                        calendarIframeContainer.classList.remove("loading");
                    };
                });
            }

              window.toggleDropdown = function(element) {
                const icon = element.querySelector('.dropdown-icon');
                icon.classList.toggle('rotate');
            };

            // Toggle dropdown icon rotation
            function toggleDropdown(element) {
                const icon = element.querySelector('.dropdown-icon');
                icon.classList.toggle('rotate');
            }
        });
   