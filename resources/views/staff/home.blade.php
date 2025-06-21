<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Letty's Birthing Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/imglogo.png') }}"> 
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            font-size: 14px;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(to bottom, rgb(50, 134, 104) 0%, #2d5a3d 50%, #0f2419 100%);
            padding: 20px 0;
            color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar.mobile-hidden {
            transform: translateX(-100%);
        }
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: #2c3e50;
            cursor: pointer;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar .logo-section {
            text-align: center;
            padding: 0 20px 30px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
         .sidebar .logo-section {
            text-align: center;
            padding: 0 20px 30px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.2);
        }
        .sidebar .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .sidebar h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        .sidebar a {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #81C784;
        }
        .sidebar a i {
            width: 20px;
            margin-right: 10px;
        }
        .content {
            margin-left: 240px;
            padding: 0;
            min-height: 100vh;
        }
        .header {
            background-color: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            z-index: 500;
            height: 75px;
        }
        .header h4 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 18px;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            border: 1px solid #ddd;
            border-radius: 25px;
            padding: 8px 15px;
            width: 250px;
            font-size: 14px;
        }
        .notification-icon {
            position: relative;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .notification-icon:hover {
            background: #e9ecef;
            color: #495057;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .user-profile {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .main-content {
            padding: 30px;
            margin-top: 70px;
        }
        .stats-row {
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .stat-card h5 {
            margin: 8px 0 5px 0;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
        }
        .calendar-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .calendar-header {
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .calendar-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .calendar-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }
        .nav-btn:hover {
            background: rgb(50, 134, 104);
            color: white;
            border-color: rgb(50, 134, 104);
        }
        .today-btn {
            padding: 8px 15px;
            background: rgb(50, 134, 104);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .today-btn:hover {
            background: #2d5a3d;
        }
        .view-controls {
            display: flex;
            gap: 5px;
        }
        .view-btn {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }
        .view-btn.active {
            background: rgb(50, 134, 104);
            color: white;
            border-color: rgb(50, 134, 104);
        }
        .calendar-month {
            padding: 20px 30px;
        }
        .month-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .month-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            flex: 1;
            text-align: center;
        }
        .calendar-controls-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        .calendar-header-cell {
            background: #f8f9fa;
            padding: 12px 8px;
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-align: center;
        }
        .calendar-cell {
            background: white;
            min-height: 70px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .calendar-cell:hover {
            background: #f8f9fa;
        }
        .calendar-cell.today {
            background: rgba(50, 134, 104, 0.1);
            border: 2px solid rgb(50, 134, 104);
        }
        .calendar-cell.other-month {
            color: #ccc;
            background: #fafafa;
        }
        .date-number {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .appointment-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-bottom: 2px;
        }
        .appointment-dot.time-1030 { background: #2196F3; }
        .appointment-dot.time-1430 { background: #FF9800; }
        .appointment-text {
            font-size: 9px;
            color: #6c757d;
            line-height: 1.2;
            margin-bottom: 1px;
        }

        /* Color scheme for stat icons matching admin dashboard */
        .stat-icon.patients { color: rgb(50, 134, 104); }
        .stat-icon.appointments { color: #2196F3; }
        .stat-icon.due { color: #FF9800; }
        .stat-icon.pending { color: #9C27B0; }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 200px;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                left: 200px;
            }
        }
        
        @media (max-width: 992px) {
            .stats-row .col-3 {
                width: 50%;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 240px;
            }
            .sidebar.mobile-show {
                transform: translateX(0);
            }
            .sidebar-overlay.show {
                display: block;
            }
            .content {
                margin-left: 0;
            }
            .mobile-menu-btn {
                display: block;
            }
            .header {
                padding: 15px 20px;
                left: 0;
                width: 100%;
            }
            .header h4 {
                font-size: 16px;
            }
            .main-content {
                padding: 15px;
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }
            .stats-row {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 15px !important;
                width: 100% !important;
                max-width: 400px !important;
                margin-bottom: 30px;
            }
            .stats-row .col-3 {
                width: 100% !important;
                max-width: 320px !important;
                margin: 0 !important;
                padding: 0 !important;
                flex: none !important;
            }
            .stat-card {
                height: 100px;
                padding: 15px;
                width: 100%;
            }
            .stat-card h3 {
                font-size: 24px;
            }
            .stat-icon {
                font-size: 20px;
            }
            .calendar-section {
                width: 100%;
                max-width: 400px;
            }
            .calendar-header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            .calendar-controls {
                justify-content: space-between;
            }
            .calendar-month {
                padding: 15px 20px;
            }
            .month-header {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
            .month-title {
                text-align: center;
                order: 1;
            }
            .calendar-controls-left {
                order: 2;
            }
            .view-controls {
                order: 3;
            }
            .calendar-cell {
                min-height: 50px;
                padding: 5px;
            }
            .calendar-header-cell {
                padding: 8px 5px;
                font-size: 11px;
            }
        }
        
        @media (max-width: 480px) {
            .header h4 {
                font-size: 14px;
            }
            .main-content {
                padding: 10px;
            }
            .stats-row {
                max-width: 350px !important;
            }
            .stats-row .col-3 {
                max-width: 300px !important;
            }
            .stat-card {
                height: 90px;
                padding: 10px;
            }
            .stat-card h3 {
                font-size: 20px;
            }
            .stat-card h5 {
                font-size: 11px;
            }
            .calendar-section {
                max-width: 350px;
            }
            .calendar-month {
                padding: 12px 15px;
            }
            .calendar-cell {
                min-height: 45px;
            }
            .view-controls {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 360px) {
            .main-content {
                padding: 8px;
            }
            .stats-row {
                max-width: 320px !important;
            }
            .stats-row .col-3 {
                max-width: 280px !important;
            }
            .calendar-section {
                max-width: 320px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" onclick="toggleMobileMenu()"></div>
    
    <div class="sidebar">
        <div class="logo-section">
            <div class="logo">
                 <img src="{{ asset('img/imglogo.png') }}" alt="Logo">
            </div>
            <h5>Letty's Birthing Home</h5>
        </div>
        
        <a href="{{ route('homes') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('staffPatients') }} " ><i class="fas fa-users"></i> Patients</a>
        <a href="{{ route('staffAppointments') }} "><i class="fas fa-calendar-alt"></i>Appointment</a>      
        <a href="{{ route('bills') }}"><i class="fas fa-file-invoice-dollar"></i>Bill</a>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Staff Dashboard</h4>
            </div>
            
            <div class="header-right">
               
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                
                <div class="user-profile">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="row stats-row">
                <div class="col-3">
                    <div class="stat-card">
                        <div class="stat-icon patients">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>69</h3>
                        <h5>Total Patients</h5>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="stat-card">
                        <div class="stat-icon appointments">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3>3</h3>
                        <h5>Today's Appointments</h5>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="stat-card">
                        <div class="stat-icon due">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3>4</h3>
                        <h5>Due This Week</h5>
                    </div>
                </div>
                
                <div class="col-3">
                    <div class="stat-card">
                        <div class="stat-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>0</h3>
                        <h5>Pending Appointments</h5>
                    </div>
                </div>
            </div>

            <div class="calendar-section">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <i class="fas fa-calendar-alt"></i>
                        Appointment Calendar
                    </div>
                </div>
                
                <div class="calendar-month">
                    <div class="month-header">
                        <div class="calendar-controls-left">
                            <button class="nav-btn" onclick="previousMonth()">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="nav-btn" onclick="nextMonth()">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <button class="today-btn" onclick="goToToday()">Today</button>
                        </div>
                        
                        <div class="month-title" id="monthTitle">May 2025</div>
                        
                        <div class="view-controls">
                            <button class="view-btn active">Month</button>
                            <button class="view-btn">Week</button>
                            <button class="view-btn">Day</button>
                        </div>
                    </div>
                    
                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Calendar will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calendar functionality
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        // Sample appointments data
        const appointments = {
            '2025-05-08': [
                { time: '10:30', patient: 'Ana Fe', type: 'time-1030' },
                { time: '14:30', patient: 'Ana Fe', type: 'time-1430' }
            ],
            '2025-05-20': [
                { time: '10:30', patient: 'John Doe', type: 'time-1030' }
            ],
            '2025-06-19': [
                { time: '09:00', patient: 'Today Patient', type: 'time-1030' }
            ]
        };
        
        function generateCalendar(month, year) {
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();
            
            let calendarHTML = '';
            
            // Add day headers
            daysOfWeek.forEach(day => {
                calendarHTML += `<div class="calendar-header-cell">${day}</div>`;
            });
            
            // Add empty cells for days before the first day of the month
            for (let i = firstDay - 1; i >= 0; i--) {
                const date = daysInPrevMonth - i;
                calendarHTML += `<div class="calendar-cell other-month">
                    <div class="date-number">${date}</div>
                </div>`;
            }
            
            // Add days of the current month
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isToday = today.getDate() === day && today.getMonth() === month && today.getFullYear() === year;
                const todayClass = isToday ? 'today' : '';
                
                let appointmentHTML = '';
                if (appointments[dateString]) {
                    appointments[dateString].forEach(appointment => {
                        appointmentHTML += `
                            <div class="appointment-dot ${appointment.type}"></div>
                            <div class="appointment-text">${appointment.time} ${appointment.patient}</div>
                        `;
                    });
                }
                
                calendarHTML += `<div class="calendar-cell ${todayClass}">
                    <div class="date-number">${day}</div>
                    ${appointmentHTML}
                </div>`;
            }
            
            // Add empty cells for days after the last day of the month
            const totalCells = Math.ceil((firstDay + daysInMonth) / 7) * 7;
            const remainingCells = totalCells - (firstDay + daysInMonth);
            for (let day = 1; day <= remainingCells; day++) {
                calendarHTML += `<div class="calendar-cell other-month">
                    <div class="date-number">${day}</div>
                </div>`;
            }
            
            document.getElementById('calendarGrid').innerHTML = calendarHTML;
            document.getElementById('monthTitle').textContent = `${months[month]} ${year}`;
        }
        
        function previousMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        }
        
        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        }
        
        function goToToday() {
            const today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            generateCalendar(currentMonth, currentYear);
        }
        
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }
        
        // View button functionality
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Close mobile menu when clicking on menu items
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMobileMenu();
                }
            });
        });
        
        // Initialize calendar
        generateCalendar(currentMonth, currentYear);
    </script>
</body>
</html>