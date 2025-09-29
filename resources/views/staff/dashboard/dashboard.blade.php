<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Letty's Birthing Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/imglogo.png') }}">

    <style>
        :root {
            --primary-color: #113F67;
            --primary-dark: #0d2f4d;
            --primary-light: #1a4d7a;
            --primary-gradient: linear-gradient(135deg, #113F67 0%, #0d2f4d 100%);
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: var(--primary-gradient);
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(20px);
            transition: var(--transition);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            width: var(--sidebar-width);
            z-index: 1050;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: var(--transition);
            border-radius: var(--border-radius);
            margin: 2px 0;
            font-weight: 500;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #fff;
            transform: scaleY(0);
            transition: var(--transition);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link i {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            min-width: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: #fff;
        }

        .dropdown-submenu {
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--border-radius);
            margin-top: 4px;
            padding: 8px;
            margin-left: 10px;
        }

        .dropdown-submenu .nav-link {
            font-size: 0.9em;
            padding: 8px 12px;
            margin: 1px 0;
        }

        .dropdown-icon {
            margin-left: auto;
            color: rgba(255, 255, 255, 0.8);
            transition: transform var(--transition), color var(--transition);
        }

        .dropdown-icon.rotate {
            transform: rotate(180deg);
        }

        .content {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: var(--sidebar-width);
                transform: translateX(-100%);
                z-index: 1055;
                overflow-y: auto;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
                width: var(--sidebar-width);
                z-index: 1055;
            }

            .content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1040;
                display: none;
                backdrop-filter: blur(4px);
            }

            .sidebar-overlay.show {
                display: block;
                animation: fadeIn 0.3s ease-out;
            }

            .sidebar-header {
                position: relative;
                padding: 15px;
            }

            .logo-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            #mobileMenuBtnSidebar {
                position: absolute;
                top: -10px;
                right: 15px;
                background: none;
                border: none;
                color: #fff;
                font-size: 1.5rem;
                padding: 8px;
                border-radius: 8px;
                transition: var(--transition);
                z-index: 1060;
                cursor: pointer;
            }

            #mobileMenuBtnSidebar:hover {
                background: rgba(255, 255, 255, 0.1);
                transform: scale(1.1);
            }
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            color: white;
            font-size: 26px;
            position: relative;
            overflow: hidden;
        }

        .stat-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover .stat-icon::before {
            left: 100%;
        }

        .stat-icon.patients {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
        }

        .stat-icon.appointments {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, #34d399, #059669);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .main-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: var(--card-shadow);
        }

        .page-title {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .notification-dropdown {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow-hover);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
            min-width: 350px;
            max-width: 90vw;
            max-height: 70vh;
            overflow-y: auto;
        }

        @media (max-width: 576px) {

            .notification-dropdown,
            .profile-dropdown {
                position: fixed !important;
                top: 60px;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                min-width: unset !important;
                max-width: unset !important;
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                z-index: 1050;
                max-height: 70vh;
                overflow-y: auto;
            }
        }

        .notification-dropdown li {
            list-style: none;
        }

        .notification-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mobile-menu-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            padding: 8px;
            border-radius: 8px;
            transition: var(--transition);
            z-index: 1060;
            cursor: pointer;
        }

        .mobile-menu-btn:hover {
            background: rgba(17, 63, 103, 0.1);
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .user-profile img {
            border: 2px solid rgba(17, 63, 103, 0.2);
            transition: var(--transition);
        }

        .user-profile:hover img {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .profile-dropdown {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow-hover);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }

        .profile-dropdown-item {
            transition: var(--transition);
            padding: 12px 20px;
        }

        .profile-dropdown-item:hover {
            background: #ffffff;
            transform: translateX(4px);
        }

        /* Custom Calendar Styles */
        .calendar-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-top: 24px;
            position: relative;
            overflow: hidden;
        }

        .calendar-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
            flex-wrap: wrap;
            gap: 10px;
        }

        .calendar-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #calendarMonthYear {
            font-size: 1.4rem;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .calendar-title i {
            color: var(--primary-dark);
        }

        .calendar-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .calendar-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .calendar-btn:hover {
            background: linear-gradient(135deg, #0d2f4d 0%, #082544 100%);
            transform: scale(1.05);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            background: #ffffff;
        }

        .calendar-day-header {
            font-weight: 600;
            color: var(--primary-color);
            padding: 8px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            text-align: center;
            font-size: 0.9rem;
        }

        .calendar-day {
            padding: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            overflow: hidden;
        }

        .calendar-day:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        .calendar-day.empty {
            background: transparent;
            border: none;
            cursor: default;
            min-height: 120px;
        }

        .calendar-day.today {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            border-color: transparent;
        }

        .calendar-day.today .day-number,
        .calendar-day.today .event {
            color: white;
        }

        .calendar-day .day-number {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 4px;
            color: var(--primary-dark);
            order: -1;
        }

        .calendar-day .events-container {
            flex: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow-y: auto;
            max-height: 100%;
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }

        .calendar-day .event {
            font-size: 0.7rem;
            color: var(--primary-dark);
            background: #e6f0fa;
            padding: 2px 6px;
            border-radius: 4px;
            text-align: left;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .calendar-day.today .event {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 3px;
            }

            .calendar-day {
                padding: 8px;
                min-height: 100px;
            }

            .calendar-day.empty {
                min-height: 100px;
            }

            .calendar-day .day-number {
                font-size: 0.9rem;
            }

            .calendar-day .event {
                font-size: 0.65rem;
                padding: 2px 4px;
            }

            .calendar-title {
                font-size: 1.4rem;
            }

            #calendarMonthYear {
                font-size: 1.2rem;
            }

            .calendar-btn {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .calendar-day-header {
                font-size: 0.8rem;
                padding: 6px;
            }
        }

        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 2px;
            }

            .calendar-day {
                padding: 6px;
                min-height: 80px;
            }

            .calendar-day.empty {
                min-height: 80px;
            }

            .calendar-day .day-number {
                font-size: 0.8rem;
            }

            .calendar-day .event {
                font-size: 0.6rem;
                padding: 1px 3px;
            }

            .calendar-title {
                font-size: 1.3rem;
            }

            #calendarMonthYear {
                font-size: 1.1rem;
            }

            .calendar-btn {
                font-size: 0.75rem;
                padding: 5px 10px;
            }

            .calendar-day-header {
                font-size: 0.75rem;
                padding: 5px;
            }
        }

        @media (max-width: 576px) {
            .calendar-section {
                padding: 15px;
            }

            .calendar-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .calendar-title {
                font-size: 1.2rem;
            }

            #calendarMonthYear {
                font-size: 1rem;
            }

            .calendar-controls {
                width: 100%;
                justify-content: flex-end;
            }

            .calendar-btn {
                font-size: 0.7rem;
                padding: 4px 8px;
            }

            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 1px;
            }

            .calendar-day {
                padding: 4px;
                min-height: 60px;
            }

            .calendar-day.empty {
                min-height: 60px;
            }

            .calendar-day .day-number {
                font-size: 0.7rem;
            }

            .calendar-day .event {
                font-size: 0.55rem;
                padding: 1px 2px;
            }

            .calendar-day-header {
                font-size: 0.7rem;
                padding: 4px;
            }
        }

        @media (max-width: 400px) {
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 1px;
            }

            .calendar-day {
                padding: 3px;
                min-height: 50px;
            }

            .calendar-day.empty {
                min-height: 50px;
            }

            .calendar-day .day-number {
                font-size: 0.65rem;
            }

            .calendar-day .event {
                font-size: 0.5rem;
                padding: 1px;
            }

            .calendar-day-header {
                font-size: 0.65rem;
                padding: 3px;
            }
        }

        @media (prefers-color-scheme: dark) {
            .calendar-section {
                background: #ffffff;
                border: 1px solid #e2e8f0;
            }

            .calendar-header {
                background: #ffffff;
                border-bottom: 1px solid #e2e8f0;
            }

            .calendar-grid {
                background: #ffffff;
            }

            .calendar-day {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                color: var(--primary-dark);
            }

            .calendar-day:hover {
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .calendar-day.today {
                background: var(--primary-color);
                color: white;
            }

            .calendar-day.today .day-number,
            .calendar-day.today .event {
                color: white;
            }

            .calendar-day .day-number {
                color: var(--primary-dark);
            }

            .calendar-day .event {
                color: var(--primary-dark);
                background: #e6f0fa;
            }

            .calendar-day-header {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                color: var(--primary-color);
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .nav-link:focus,
        .btn:focus,
        .dropdown-toggle:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow-hover);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
        }

        .modal-body {
            padding: 20px;
        }

        /* Responsive Modal Styles */
        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 90vw;
                margin: 1rem auto;
            }

            .modal-content {
                border-radius: calc(var(--border-radius) * 0.75);
            }

            .modal-body {
                padding: 15px;
            }

            .modal-header {
                padding: 12px 15px;
            }

            .modal-footer {
                padding: 10px 15px;
            }

            .modal-title {
                font-size: 1.2rem;
            }

            .btn-close {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 400px) {
            .modal-dialog {
                max-width: 95vw;
                margin: 0.5rem auto;
            }

            .modal-body {
                padding: 10px;
            }

            .modal-title {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body class="bg-light">
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar position-fixed top-0 start-0 vh-100 px-0" id="sidebar">
        <div class="position-sticky pt-3">
            <div class="sidebar-header text-center pb-3 border-bottom border-light border-opacity-25 mb-3">
                <div class="logo-container">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <img src="{{ asset('img/imglogo.png') }}" alt="Logo" class="rounded-circle shadow-sm"
                            width="50" height="50">
                    </div>
                    <h6 class="text-white fw-bold mb-0">Letty's Birthing Home</h6>
                </div>
                <button class="mobile-menu-btn d-md-none" id="mobileMenuBtnSidebar" aria-label="Close sidebar menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="nav flex-column px-2">
                <a href="{{ route('staff.dashboard') }}"
                    class="nav-link mb-1 {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>

                <div class="mb-1 dropdown-menu-item {{ request()->is('patients*') ? 'open' : '' }}">
                    <a href="#patientsSubmenu" class="nav-link d-flex align-items-center" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->is('patients*') ? 'true' : 'false' }}"
                        onclick="toggleDropdown(this)">
                        <span>
                            <i class="fas fa-users me-2"></i>
                            <span>Patients</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div id="patientsSubmenu"
                        class="dropdown-submenu ms-3 collapse {{ request()->is('patients*') ? 'show' : '' }}">
                        <a href="{{ route('currentPatients') }}"
                            class="nav-link py-1 small {{ request()->routeIs('currentPatients') ? 'active' : '' }}">
                            <i class="fas fa-user me-2"></i>Current Patients
                        </a>
                        <a href="{{ route('completeVisits') }}"
                            class="nav-link py-1 small {{ request()->routeIs('completeVisits') ? 'active' : '' }}">
                            <i class="fas fa-circle-check me-2"></i>Complete Visit
                        </a>
                        <a href="{{ route('patientRecords') }}"
                            class="nav-link py-1 small {{ request()->routeIs('patientRecords') ? 'active' : '' }}">
                            <i class="fas fa-file-medical me-2"></i>Patient Records
                        </a>
                        <a href="{{ route('patientMedication.history') }}"
                            class="nav-link py-1 small {{ request()->routeIs('patientMedication.history') ? 'active' : '' }}">
                            <i class="fas fa-pills me-2"></i>Medication History
                        </a>
                    </div>
                </div>

                <div
                    class="mb-1 dropdown-menu-item {{ request()->is('staff/allAppointments*') || request()->routeIs('allAppointments') ? 'open' : '' }}">
                    <a href="#appointmentsSubmenu" class="nav-link d-flex align-items-center" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->is('staff/allAppointments*') || request()->routeIs('allAppointments') ? 'true' : 'false' }}"
                        onclick="toggleDropdown(this)">
                        <span>
                            <i class="fas fa-calendar me-2"></i>
                            <span>Appointments</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div id="appointmentsSubmenu"
                        class="dropdown-submenu ms-3 collapse {{ request()->is('staff/allAppointments*') || request()->routeIs('allAppointments') ? 'show' : '' }}">
                        <a href="{{ route('todaysAppointments') }}"
                            class="nav-link py-1 small {{ request()->routeIs('todaysAppointments') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i>Today's Appointment
                        </a>
                        <a href="{{ route('pendingAppointment') }}"
                            class="nav-link py-1 small {{ request()->routeIs('pendingAppointment') ? 'active' : '' }}">
                            <i class="fas fa-hourglass-half me-2"></i>Pending Appointment
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="content">
        <header class="main-header navbar navbar-expand-lg navbar-light sticky-top mb-4">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-btn d-md-none me-3" id="mobileMenuBtnHeader"
                        aria-label="Toggle sidebar menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="page-title mb-0">Staff Dashboard</h4>
                </div>

                <div class="d-flex align-items-center header-right">
                    <!-- Notification Dropdown -->
                    @include('partials.staff.notification')

                    <!-- Profile Dropdown -->
                    @php
                        $staff = Auth::user()->staff;
                        $avatar =
                            $staff && $staff->avatar_path ? asset($staff->avatar_path) : asset('img/adminProfile.jpg');
                    @endphp
                    <div class="dropdown user-profile">
                        <button class="btn btn-link p-1" data-bs-toggle="dropdown">
                            <img src="{{ $avatar }}" alt="Profile" class="rounded-circle" width="40"
                                height="40">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <li><a class="dropdown-item profile-dropdown-item" href="{{ route('staffProfile') }}">
                                    <i class="fas fa-user-circle me-2"></i>My Profile
                                </a></li>
                            <li><a class="dropdown-item profile-dropdown-item" href="{{ route('schedules') }}">
                                    <i class="fas fa-calendar-check me-2"></i>My Schedule
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item profile-dropdown-item text-danger" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid main-content">
            <div class="row g-4 mb-4 stats-row">
                <div class="col-6 col-lg-3">
                    <div class="stat-card card border-0 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="stat-icon patients d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="stat-number">{{ $totalPatients }}</div>
                                <div class="text-muted fw-medium">Total Patients</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card card border-0 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="stat-icon appointments d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="stat-number">{{ $todaysAppointments }}</div>
                                <div class="text-muted fw-medium">Today's Appointments</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card card border-0 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="stat-icon completed d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-circle-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="stat-number">{{ $completedVisits }}</div>
                                <div class="text-muted fw-medium">Completed Visit</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="stat-card card border-0 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="stat-icon pending d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="stat-number">{{ $pendingAppointments }}</div>
                                <div class="text-muted fw-medium">Pending Appointments</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Calendar Section -->
            <div class="calendar-section">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <span><i class="fas fa-calendar-alt"></i> Appointment Calendar</span>
                        <span id="calendarMonthYear" class="ms-2"></span>
                    </div>
                    <div class="calendar-controls">
                        <button class="calendar-btn" id="prevMonth" aria-label="Previous month">
                            <i class="fas fa-chevron-left"></i> Prev
                        </button>
                        <button class="calendar-btn" id="nextMonth" aria-label="Next month">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Event Details Modal -->
        <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Appointment Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Client:</strong> <span id="modalClient"></span></p>
                        <p><strong>Reason:</strong> <span id="modalReason"></span></p>
                        <p><strong>Time:</strong> <span id="modalTime"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="emergency-container">
            @include('partials.emergencyModal')
        </div>
    </main>
    <script>
        const appointments = @json($appointmentsData);
    </script>

    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('script/emergency.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @if (session('swal'))
            Swal.fire({
                icon: '{{ session('swal.icon') }}',
                title: '{{ session('swal.title') }}',
                text: '{{ session('swal.text') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: 'var(--primary-color)'
            });
        @endif
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");

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

            // Toggle dropdown icon rotation
            function toggleDropdown(element) {
                const icon = element.querySelector('.dropdown-icon');
                icon.classList.toggle('rotate');
            }

            // Custom Calendar functionality
            const calendarGrid = document.getElementById("calendarGrid");
            const calendarMonthYear = document.getElementById("calendarMonthYear");
            const prevMonthBtn = document.getElementById("prevMonth");
            const nextMonthBtn = document.getElementById("nextMonth");
            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            const modalClient = document.getElementById('modalClient');
            const modalReason = document.getElementById('modalReason');
            const modalTime = document.getElementById('modalTime');

            let currentDate = new Date();
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();

            // Events data
            const events = {};
            appointments.forEach(appt => {
                const dateStr = appt.date;
                const eventText = `${appt.client} - ${appt.reason} (${appt.time})`;
                if (!events[dateStr]) {
                    events[dateStr] = [];
                }
                events[dateStr].push(eventText);
            });

            function renderCalendar(month, year) {
                const today = new Date();
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                calendarMonthYear.textContent = `${monthNames[month]} ${year}`;

                calendarGrid.innerHTML = '';

                // Add day headers
                const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                days.forEach(day => {
                    const dayHeader = document.createElement("div");
                    dayHeader.classList.add("calendar-day-header");
                    dayHeader.textContent = day;
                    calendarGrid.appendChild(dayHeader);
                });

                // Get first day of the month and number of days
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // Add empty cells for days before the first day
                for (let i = 0; i < firstDay; i++) {
                    const emptyDay = document.createElement("div");
                    emptyDay.classList.add("calendar-day", "empty");
                    calendarGrid.appendChild(emptyDay);
                }

                // Add days of the month
                for (let i = 1; i <= daysInMonth; i++) {
                    const day = document.createElement("div");
                    day.classList.add("calendar-day");
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                    // Day number at the top
                    const dayNumber = document.createElement("div");
                    dayNumber.classList.add("day-number");
                    dayNumber.textContent = i;

                    if (
                        i === today.getDate() &&
                        month === today.getMonth() &&
                        year === today.getFullYear()
                    ) {
                        day.classList.add("today");
                    }

                    // Events container
                    const eventsContainer = document.createElement("div");
                    eventsContainer.classList.add("events-container");

                    // Add all events
                    if (events[dateStr]) {
                        events[dateStr].forEach(event => {
                            const eventDiv = document.createElement("div");
                            eventDiv.classList.add("event");
                            eventDiv.textContent = event;
                            eventDiv.addEventListener('click', () => {
                                const [client, reasonTime] = event.split(' - ');
                                const [reason, timePart] = reasonTime.split(' (');
                                const time = timePart ? timePart.replace(')', '') : '';
                                modalClient.textContent = client;
                                modalReason.textContent = reason;
                                modalTime.textContent = time;
                                eventModal.show();
                            });
                            eventsContainer.appendChild(eventDiv);
                        });
                    }

                    day.appendChild(dayNumber);
                    day.appendChild(eventsContainer);
                    calendarGrid.appendChild(day);
                }
            }

            // Initial render with current date (September 23, 2025, 08:26 PM PST)
            currentDate = new Date("2025-09-23T20:26:00-07:00");
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
            renderCalendar(currentMonth, currentYear);

            // Event listeners for navigation
            prevMonthBtn.addEventListener("click", () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });

            nextMonthBtn.addEventListener("click", () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });
        });
    </script>
</body>

</html>
