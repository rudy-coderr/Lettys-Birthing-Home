document.addEventListener("DOMContentLoaded", function () {
    // Sidebar toggle
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const btnOpen = document.getElementById("mobileMenuBtnHeader");
    const btnClose = document.getElementById("mobileMenuBtnSidebar");

    if (btnOpen && btnClose) {
        btnOpen.addEventListener("click", () => {
            sidebar.classList.add("mobile-show");
            sidebarOverlay.classList.add("show");
        });

        btnClose.addEventListener("click", () => {
            sidebar.classList.remove("mobile-show");
            sidebarOverlay.classList.remove("show");
        });

        sidebarOverlay.addEventListener("click", function () {
            sidebar.classList.remove("mobile-show");
            this.classList.remove("show");
        });
    }

    // Profile dropdown
    const userProfile = document.querySelector(".user-profile button");
    const profileDropdown = document.querySelector(".profile-dropdown");
    if (userProfile && profileDropdown) {
        userProfile.addEventListener("click", function (event) {
            event.stopPropagation();
            profileDropdown.classList.toggle("show");
        });

        document.addEventListener("click", function (event) {
            if (!userProfile.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.remove("show");
            }
        });
    }

    // Calendar logic
    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    // Map days from backend
    const dayMap = {
        sunday: 0, monday: 1, tuesday: 2,
        wednesday: 3, thursday: 4, friday: 5, saturday: 6
    };

    const workDaysRaw = window.staffData.workDays || [];
    const workDays = workDaysRaw
        .map(day => dayMap[day.toLowerCase()])
        .filter(day => day !== undefined);

    const shiftType = (window.staffData.shiftType || "day").toLowerCase();

   function generateShiftsForLatestWeek(month, year) {
    const shifts = {};

    // Find start of current week (Sunday)
    const today = new Date();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay());
    startOfWeek.setHours(0, 0, 0, 0);

    // End of week (Saturday)
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);

    // Loop through each day of the week
    for (let d = new Date(startOfWeek); d <= endOfWeek; d.setDate(d.getDate() + 1)) {
        if (d.getMonth() === month && d.getFullYear() === year) {
            const dayIndex = d.getDay();
            if (workDays.includes(dayIndex)) {
                const dateString = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
                shifts[dateString] = [{
                    type: shiftType,
                    start: shiftType === "night" ? "20:00" : "08:00",
                    end: shiftType === "night" ? "04:00" : "16:00"
                }];
            }
        }
    }
    return shifts;
}


    function generateCalendar(month, year) {
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
       const shifts = generateShiftsForLatestWeek(month, year);


        let calendarHTML = "";
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        daysOfWeek.forEach(day => {
            calendarHTML += `<div class="calendar-header-cell">${day}</div>`;
        });

        let dayCount = 1;
        let nextMonthDay = 1;

        for (let i = 0; i < 6; i++) {
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    const prevMonthDayNum = daysInPrevMonth - (firstDay - j - 1);
                    calendarHTML += `<div class="calendar-cell other-month"><div class="date-number">${prevMonthDayNum}</div></div>`;
                } else if (dayCount > daysInMonth) {
                    calendarHTML += `<div class="calendar-cell other-month"><div class="date-number">${nextMonthDay}</div></div>`;
                    nextMonthDay++;
                } else {
                    const thisDate = new Date(year, month, dayCount);
                    thisDate.setHours(0, 0, 0, 0);
                    const dateString = `${year}-${String(month + 1).padStart(2, "0")}-${String(dayCount).padStart(2, "0")}`;

                    const isToday = thisDate.getTime() === today.getTime();
                    const todayClass = isToday ? "today" : "";
                    const workDayClass = shifts[dateString] ? "work-day" : "";

                    let shiftHTML = "";
                    if (shifts[dateString]) {
                        shifts[dateString].forEach(shift => {
                            const dotClass = shift.type === "night" ? "night" : "day";
                            shiftHTML += `
                                <div class="shift-dot ${dotClass}"></div>
                                <div class="shift-text">${shift.start} - ${shift.end} (${shift.type.charAt(0).toUpperCase() + shift.type.slice(1)} Shift)</div>
                            `;
                        });
                    }

                    calendarHTML += `<div class="calendar-cell ${todayClass} ${workDayClass}">
                        <div class="date-number">${dayCount}</div>
                        ${shiftHTML}
                    </div>`;
                    dayCount++;
                }
            }
        }

        document.getElementById("calendarGrid").innerHTML = calendarHTML;
        document.getElementById("monthTitle").textContent = `${months[month]} ${year}`;
    }

    // Calendar controls
    window.previousMonth = function () {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar(currentMonth, currentYear);
    };

    window.nextMonth = function () {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar(currentMonth, currentYear);
    };

    window.goToToday = function () {
        currentDate = new Date();
        currentMonth = currentDate.getMonth();
        currentYear = currentDate.getFullYear();
        generateCalendar(currentMonth, currentYear);
    };

    generateCalendar(currentMonth, currentYear);
});
