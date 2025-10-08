// add-appointment.js

document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded successfully');
    
    // Sidebar functionality
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    // Open sidebar
    if (btnOpen) {
        btnOpen.addEventListener("click", function() {
            sidebar.classList.add("mobile-show");
            sidebarOverlay.classList.add("show");
        });
    }

    // Close sidebar
    if (btnClose) {
        btnClose.addEventListener("click", function() {
            sidebar.classList.remove("mobile-show");
            sidebarOverlay.classList.remove("show");
        });
    }

    // Close when clicking overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function() {
            sidebar.classList.remove("mobile-show");
            this.classList.remove("show");
        });
    }

    // Appointment time slot functionality
    const branchSelect = document.getElementById('branch');
    const dateInput = document.getElementById('appointmentDate');
    const timeSelect = document.getElementById('appointmentTime');
    const timeSlotHelp = document.getElementById('timeSlotHelp');
    const form = document.getElementById('addAppointmentForm');

    console.log('Elements found:', {
        branchSelect: !!branchSelect,
        dateInput: !!dateInput,
        timeSelect: !!timeSelect,
        timeSlotHelp: !!timeSlotHelp,
        availableSlotsUrl: typeof availableSlotsUrl !== 'undefined' ? availableSlotsUrl : 'NOT DEFINED'
    });

    // Function to get branch ID from selected option
    function getSelectedBranchId() {
        const selectedOption = branchSelect.options[branchSelect.selectedIndex];
        return selectedOption.getAttribute('data-branch-id');
    }

    // Function to fetch and populate available time slots
    async function loadAvailableSlots() {
        const date = dateInput.value;
        const branchId = getSelectedBranchId();

        // Reset time select if either field is empty
        if (!date || !branchId) {
            timeSelect.disabled = true;
            timeSelect.innerHTML = '<option value="" disabled selected>Select date and branch first</option>';
            timeSlotHelp.style.display = 'none';
            return;
        }

        // Show loading state
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        timeSlotHelp.style.display = 'block';
        timeSlotHelp.className = 'time-slot-info loading';
        timeSlotHelp.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading available time slots...';

        try {
            const response = await fetch(`${availableSlotsUrl}?date=${date}&branch_id=${branchId}`);
            const data = await response.json();

            // Clear existing options
            timeSelect.innerHTML = '';

            if (data.slots && data.slots.length > 0) {
                // Add default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select a time slot';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                timeSelect.appendChild(defaultOption);

                // Add available slots
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.value;
                    option.textContent = slot.label;
                    timeSelect.appendChild(option);
                });

                timeSelect.disabled = false;
                timeSlotHelp.style.display = 'block';
                timeSlotHelp.className = 'time-slot-info success';
                timeSlotHelp.innerHTML = `<i class="fas fa-check-circle"></i> ${data.slots.length} time slot(s) available`;
            } else {
                // No slots available
                const noSlotsOption = document.createElement('option');
                noSlotsOption.value = '';
                noSlotsOption.textContent = 'No available time slots';
                noSlotsOption.disabled = true;
                noSlotsOption.selected = true;
                timeSelect.appendChild(noSlotsOption);
                
                timeSelect.disabled = true;
                timeSlotHelp.style.display = 'block';
                timeSlotHelp.className = 'time-slot-info warning';
                timeSlotHelp.innerHTML = '<i class="fas fa-exclamation-circle"></i> No available time slots for this date and branch';
            }
        } catch (error) {
            console.error('Error loading time slots:', error);
            timeSelect.innerHTML = '<option value="" disabled selected>Error loading slots</option>';
            timeSlotHelp.style.display = 'block';
            timeSlotHelp.className = 'time-slot-info error';
            timeSlotHelp.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error loading time slots. Please try again.';
        }
    }

    // Event listeners
    branchSelect.addEventListener('change', function() {
        console.log('Branch changed:', branchSelect.value);
        loadAvailableSlots();
    });
    
    dateInput.addEventListener('change', function() {
        console.log('Date changed:', dateInput.value);
        loadAvailableSlots();
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
});

// Toggle dropdown function (for sidebar)
function toggleDropdown(element) {
    const icon = element.querySelector('.dropdown-icon');
    if (icon) {
        icon.classList.toggle('rotate');
    }
}