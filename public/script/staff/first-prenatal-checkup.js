document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");
    const immunizationSection = document.getElementById("immunizationSection");
    const immunizationRemarksSection = document.getElementById("immunizationRemarksSection");
    const showImmunizationCheckbox = document.getElementById("showImmunizationSection");
    const addVaccineBtn = document.getElementById("addVaccineBtn");
    const immunizationEntries = document.getElementById("immunizationEntries");
    let vaccineCount = 0;

    // 🔑 Generate vaccine options from JSON
   function getVaccineOptions() {
    return vaccines.map(v => 
        `<option value="${v.id}">${v.item_name} (Stock: ${v.quantity})</option>`
    ).join('');
}


    // Sidebar toggle
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

    // Toggle Immunization & Remarks
    showImmunizationCheckbox.addEventListener("change", function() {
        if (this.checked) {
            immunizationSection.classList.remove("hidden-section");
            immunizationRemarksSection.classList.remove("hidden-section");
            if (immunizationEntries.children.length === 0) {
                addVaccineEntry();
            }
        } else {
            immunizationSection.classList.add("hidden-section");
            immunizationRemarksSection.classList.add("hidden-section");
            immunizationEntries.innerHTML = '';
            vaccineCount = 0;
        }
    });

    // Add vaccine entry
    function addVaccineEntry() {
        vaccineCount++;
        const entryId = `vaccineEntry${vaccineCount}`;

        const entryHtml = `
            <div class="vaccine-entry" id="${entryId}">
                <div class="form-row">
                    <div class="form-group">
                        <label for="vaccineType${vaccineCount}">Vaccine Type <span class="required">*</span></label>
                        <select class="form-select" id="vaccineType${vaccineCount}" name="vaccines[${vaccineCount}][item_id]" required>
                            <option value="" disabled selected>Select Vaccine</option>
                            ${getVaccineOptions()}
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

        // Toggle "Other" field
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

    // Remove vaccine entry
    window.removeVaccineEntry = function(entryId) {
        const entry = document.getElementById(entryId);
        if (entry) {
            entry.remove();
        }
    };

    // Add entry on button click
    addVaccineBtn.addEventListener("click", addVaccineEntry);

    // Form validation + confirmation
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

    // Clear form
    function clearForm(formId) {
        const form = document.getElementById(formId);
        form.reset();
        form.classList.remove('was-validated');
        immunizationSection.classList.add("hidden-section");
        immunizationRemarksSection.classList.add("hidden-section");
        immunizationEntries.innerHTML = '';
        vaccineCount = 0;
        showImmunizationCheckbox.checked = false;
        document.getElementById('prenatalStatusId').value = 1;
    }

    // Set completed
    function setCompleted() {
        document.getElementById('prenatalStatusId').value = 2;
    }
});

// Dropdown toggle
function toggleDropdown(element) {
    const icon = element.querySelector('.dropdown-icon');
    icon.classList.toggle('rotate');
    const submenu = element.nextElementSibling;
    submenu.classList.toggle('show');
}
