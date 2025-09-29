
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");
            const patientForm = document.getElementById("patientForm");
            const showImmunizationCheckbox = document.getElementById("showImmunizationSection");
            const immunizationSection = document.getElementById("immunizationSection");
            const immunizationRemarksSection = document.getElementById("immunizationRemarksSection");
            const addVaccineBtn = document.getElementById("addVaccineBtn");
            const immunizationEntries = document.getElementById("immunizationEntries");

            let vaccineCount = 0;

            // Sidebar toggle for mobile
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

            // Toggle Immunization & Remarks visibility
            showImmunizationCheckbox.addEventListener("change", function() {
                if (this.checked) {
                    immunizationSection.classList.remove("section-hidden");
                    immunizationRemarksSection.classList.remove("section-hidden");
                    if (immunizationEntries.children.length === 0) {
                        addVaccineEntry();
                    }
                } else {
                    immunizationSection.classList.add("section-hidden");
                    immunizationRemarksSection.classList.add("section-hidden");
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
                            <div class="form-group section-hidden" id="otherVaccineContainer${vaccineCount}">
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
                        otherVaccineContainer.classList.remove("section-hidden");
                        document.getElementById(`otherVaccine${vaccineCount}`).setAttribute("required", "required");
                    } else {
                        otherVaccineContainer.classList.add("section-hidden");
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

            // Form validation for patient form
            patientForm.addEventListener("submit", function(event) {
                event.preventDefault();
                if (!patientForm.checkValidity()) {
                    patientForm.classList.add("was-validated");
                    return;
                }

                const showImmunization = showImmunizationCheckbox.checked;
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
                        patientForm.classList.add("was-validated");
                        patientForm.submit();
                    }
                });
            });

            // Toggle dropdown
            window.toggleDropdown = function(element) {
                const icon = element.querySelector('.dropdown-icon');
                icon.classList.toggle('rotate');
                const submenu = element.nextElementSibling;
                submenu.classList.toggle('show');
            };

            // Show Prenatal Section
            window.showPrenatalSection = function() {
                document.getElementById("patientSection").classList.add("section-hidden");
                document.getElementById("prenatalSection").classList.remove("section-hidden");
            };

            // Show Patient Section
            window.showPatientSection = function() {
                document.getElementById("prenatalSection").classList.add("section-hidden");
                document.getElementById("patientSection").classList.remove("section-hidden");
            };

            // Next Step with Validation
            window.nextStep = function() {
                if (!patientForm.checkValidity()) {
                    patientForm.classList.add("was-validated");
                    return;
                }
                showPrenatalSection();
            };

            // Cancel Form
            window.cancelForm = function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will lose all unsaved changes.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel!',
                    cancelButtonText: 'No, keep editing',
                    confirmButtonColor: 'var(--danger-color)',
                    cancelButtonColor: 'var(--primary-color)',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('currentPatients') }}";
                    }
                });
            };
        });
  