document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    const immunizationSection = document.getElementById("immunizationSection");
    const showImmunizationCheckbox = document.getElementById("showImmunizationSection");
    const addVaccineBtn = document.getElementById("addVaccineBtn");
    const immunizationEntries = document.getElementById("immunizationEntries");
    const remarksSection = document.getElementById("remarksSection");

    let vaccineCount = 0;

    // Sidebar toggle for mobile
    if (btnOpen) {
        btnOpen.addEventListener("click", function() {
            sidebar.classList.add("mobile-show");
            sidebarOverlay.classList.add("show");
        });
    }
    
    if (btnClose) {
        btnClose.addEventListener("click", function() {
            sidebar.classList.remove("mobile-show");
            sidebarOverlay.classList.remove("show");
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function() {
            sidebar.classList.remove("mobile-show");
            this.classList.remove("show");
        });
    }

    // Toggle Immunization & Remarks visibility
    showImmunizationCheckbox.addEventListener("change", function() {
        if (this.checked) {
            immunizationSection.classList.remove("hidden-section");
            remarksSection.classList.remove("hidden-section");
            if (immunizationEntries.children.length === 0) {
                addVaccineEntry();
            }
        } else {
            immunizationSection.classList.add("hidden-section");
            remarksSection.classList.add("hidden-section");
            immunizationEntries.innerHTML = '';
            vaccineCount = 0;
        }
    });

    // Function to add a new vaccine entry
    function addVaccineEntry() {
        vaccineCount++;
        const entryId = `vaccineEntry${vaccineCount}`;

        let optionsHtml = `<option value="" disabled selected>Select Vaccine</option>`;
        vaccines.forEach(vaccine => {
            optionsHtml += `<option value="${vaccine.id}">${vaccine.item_name} (Stock: ${vaccine.quantity})</option>`;
        });
        optionsHtml += `<option value="other">Other</option>`;

        const entryHtml = `
            <div class="vaccine-entry" id="${entryId}">
                <div class="form-row">
                    <div class="form-group">
                        <label for="vaccineType${vaccineCount}">Vaccine Type <span class="required">*</span></label>
                        <select class="form-select" id="vaccineType${vaccineCount}" name="vaccines[${vaccineCount}][item_id]" required>
                            ${optionsHtml}
                        </select>
                        <div class="invalid-feedback">Vaccine type is required</div>
                    </div>
                    <div class="form-group hidden-section" id="otherVaccineContainer${vaccineCount}">
                        <label for="otherVaccine${vaccineCount}">Specify Vaccine Type <span class="required">*</span></label>
                        <input type="text" class="form-control" id="otherVaccine${vaccineCount}" name="vaccines[${vaccineCount}][other_type]" placeholder="Enter custom vaccine type">
                        <div class="invalid-feedback">Custom vaccine type is required</div>
                    </div>
                    <div class="form-group">
                        <label for="vaccineDate${vaccineCount}">Date Administered <span class="required">*</span></label>
                        <input type="date" class="form-control" id="vaccineDate${vaccineCount}" name="vaccines[${vaccineCount}][date]" required>
                        <div class="invalid-feedback">Date administered is required</div>
                    </div>
                    <div class="form-group">
                        <label for="vaccineQuantity${vaccineCount}">Quantity (Doses) <span class="required">*</span></label>
                        <input type="number" class="form-control" id="vaccineQuantity${vaccineCount}" name="vaccines[${vaccineCount}][quantity]" min="1" required>
                        <div class="invalid-feedback">Quantity is required and must be at least 1</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <button type="button" class="btn-remove" onclick="removeVaccineEntry('${entryId}')">
                            <i class="fas fa-trash me-2"></i>Remove
                        </button>
                    </div>
                </div>
                <hr>
            </div>
        `;

        immunizationEntries.insertAdjacentHTML('beforeend', entryHtml);

        // Toggle "Other" field visibility
        const vaccineTypeSelect = document.getElementById(`vaccineType${vaccineCount}`);
        const otherVaccineContainer = document.getElementById(`otherVaccineContainer${vaccineCount}`);
        vaccineTypeSelect.addEventListener("change", function() {
            if (this.value === "other") {
                otherVaccineContainer.classList.remove("hidden-section");
                document.getElementById(`otherVaccine${vaccineCount}`).setAttribute("required", "required");
            } else {
                otherVaccineContainer.classList.add("hidden-section");
                document.getElementById(`otherVaccine${vaccineCount}`).removeAttribute("required");
            }
        });
    }

    // Function to remove a vaccine entry
    window.removeVaccineEntry = function(entryId) {
        const entry = document.getElementById(entryId);
        if (entry) {
            entry.remove();
        }
    };

    // Add vaccine entry on button click
    addVaccineBtn.addEventListener("click", addVaccineEntry);

    // Form submission with validation
    const form = document.getElementById("prenatalForm");
    form.addEventListener("submit", function(event) {
        event.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        }

        const showImmunization = document.getElementById("showImmunizationSection").checked;
        let message = showImmunization ?
            'Immunization details have been added. Proceed with submission?' :
            'No immunization details were added. Are you sure you want to proceed?';
        let icon = showImmunization ? 'question' : 'warning';

        Swal.fire({
            title: 'Confirm Submission',
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#113F67',
            cancelButtonColor: '#ef4444',
        }).then((result) => {
            if (result.isConfirmed) {
                form.classList.add("was-validated");
                form.submit();
            }
        });
    });

    // ========================================
    // TIME SLOT FUNCTIONALITY FOR NEXT VISIT
    // ========================================
    const branchSelect = document.getElementById('nextVisitBranch');
    const dateInput = document.getElementById('nextVisit');
    const timeSelect = document.getElementById('nextVisitTime');
    const timeSlotHelp = document.getElementById('visitTimeSlotHelp');

    if (branchSelect && dateInput && timeSelect && timeSlotHelp) {
        async function loadVisitSlots() {
            const date = dateInput.value;
            const branchId = branchSelect.value;

            if (!date || !branchId) {
                timeSelect.disabled = true;
                timeSelect.innerHTML = '<option value="">Select branch and date first</option>';
                timeSlotHelp.style.display = 'none';
                return;
            }

            timeSelect.disabled = true;
            timeSelect.innerHTML = '<option value="">Loading...</option>';
            timeSlotHelp.style.display = 'block';
            timeSlotHelp.className = 'time-slot-info loading';
            timeSlotHelp.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading available time slots...';

            try {
                const response = await fetch(`${visitSlotsUrl}?date=${date}&branch_id=${branchId}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();

                timeSelect.innerHTML = '';

                if (data.slots && data.slots.length > 0) {
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select a time slot';
                    timeSelect.appendChild(defaultOption);

                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.value;
                        option.textContent = slot.label;
                        timeSelect.appendChild(option);
                    });

                    timeSelect.disabled = false;
                    timeSlotHelp.className = 'time-slot-info success';
                    timeSlotHelp.innerHTML = `<i class="fas fa-check-circle"></i> ${data.slots.length} time slot(s) available`;
                } else {
                    timeSelect.innerHTML = '<option value="">No available time slots</option>';
                    timeSlotHelp.className = 'time-slot-info warning';
                    timeSlotHelp.innerHTML = '<i class="fas fa-exclamation-circle"></i> No available time slots for this date and branch';
                }
            } catch (error) {
                console.error('Error loading time slots:', error);
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
                timeSlotHelp.className = 'time-slot-info error';
                timeSlotHelp.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error loading time slots. Please try again.';
            }
        }

        branchSelect.addEventListener('change', loadVisitSlots);
        dateInput.addEventListener('change', loadVisitSlots);
    }
});

// Dropdown toggle
function toggleDropdown(element) {
    const icon = element.querySelector('.dropdown-icon');
    if (icon) {
        icon.classList.toggle('rotate');
    }
    const submenu = element.nextElementSibling;
    if (submenu) {
        submenu.classList.toggle('show');
    }
}

// Set status = Completed
function setCompleted() {
    document.getElementById('prenatalStatusId').value = 2;
}