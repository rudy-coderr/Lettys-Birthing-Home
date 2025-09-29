
     

        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");
            const form = document.getElementById("billForm");

            btnOpen?.addEventListener("click", () => {
                sidebar.classList.add("mobile-show");
                sidebarOverlay.classList.add("show");
            });
            btnClose?.addEventListener("click", () => {
                sidebar.classList.remove("mobile-show");
                sidebarOverlay.classList.remove("show");
            });
            sidebarOverlay?.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                this.classList.remove("show");
            });

            form?.addEventListener("submit", function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add("was-validated");
                    return;
                }

                // Allow normal form submit
                form.classList.add("was-validated");
            });

        });

        function toggleDropdown(element) {
            const icon = element.querySelector('.dropdown-icon');
            icon.classList.toggle('rotate');
            const submenu = element.nextElementSibling;
            submenu.classList.toggle('show');
        }

        let itemRowIndex = 1; // <-- IDAGDAG ITO

        function addItemRow() {
            const table = document.getElementById("itemsTable").getElementsByTagName("tbody")[0];
            const row = table.insertRow();

            // Kunin yung mga <option> sa hidden template
            const itemOptions = document.getElementById("itemOptions").innerHTML;

            row.innerHTML = `
        <td>
            <select name="items[${itemRowIndex}][type]" class="item-type form-select" required onchange="updateItemOptions(this)">
                <option value="" disabled selected>Select medication type</option>
                <option value="medicine">Medicine</option>
                <option value="supply">Medical Supply</option>
            </select>
        </td>
        <td>
            <select name="items[${itemRowIndex}][item_id]" class="item-name form-select" required>
                <option value="" disabled selected>Select a medication</option>
                ${itemOptions}
            </select>
        </td>
        <td>
            <input type="number" name="items[${itemRowIndex}][quantity]" class="item-quantity form-control" required min="1" value="1">
        </td>
        <td>
            <button type="button" class="remove-item-btn" onclick="removeItemRow(this)"><i class="fas fa-trash"></i></button>
        </td>
    `;

            itemRowIndex++; // increment kada bagong row
            updateRemoveButtons();
        }



        function removeItemRow(button) {
            button.closest("tr").remove();
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const buttons = document.querySelectorAll(".remove-item-btn");
            buttons.forEach(button => button.disabled = buttons.length === 1);
        }

        function updateItemOptions(select) {
            const row = select.closest("tr");
            const itemSelect = row.querySelector(".item-name");
            const selectedType = select.value;
            itemSelect.value = "";
            Array.from(itemSelect.options).forEach(option => {
                if (!option.value) return;
                option.style.display = option.dataset.type === selectedType || !option.dataset.type ? "block" :
                    "none";
            });
        }
   