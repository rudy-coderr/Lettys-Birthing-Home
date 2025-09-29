<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Letty's Birthing Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="icon" type="image/png" href="{{ asset('img/imglogo.png') }}">

    <style>
        :root {
            --primary-color: #113F67;
            --primary-dark: #0d2f4d;
            --primary-gradient: linear-gradient(135deg, #113F67 0%, #0d2f4d 100%);
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 250px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .sidebar {
            background: var(--primary-gradient);
            box-shadow: var(--card-shadow);
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
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
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
            transition: transform var(--transition);
        }

        .dropdown-icon.rotate {
            transform: rotate(180deg);
        }

        .content {
            margin-left: var(--sidebar-width);
            transition: var(--transition);
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
            }

            .sidebar-overlay.show {
                display: block;
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

        .main-header {
            background: rgba(255, 255, 255, 0.95);
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
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            min-width: 350px;
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
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                z-index: 1050;
                max-height: 70vh;
            }
        }

        .notification-item {
            transition: var(--transition);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        .notification-item:hover {
            background: #f0f0f0;
            transform: translateX(4px);
        }

        .notification-badge {
            background: #ef4444 !important;
            font-size: 0.7rem;
            font-weight: 600;
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
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .profile-dropdown-item {
            transition: var(--transition);
            padding: 12px 20px;
        }

        .profile-dropdown-item:hover {
            background: #ffffff;
            transform: translateX(4px);
        }

        .branch-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .branch-selector-label {
            font-size: 1rem;
            font-weight: 500;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-btn {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .filter-btn:hover {
            background: rgba(17, 63, 103, 0.1);
        }

        .filter-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            min-width: 200px;
            z-index: 1000;
        }

        .filter-dropdown-menu.show {
            display: block;
        }

        .filter-option {
            padding: 10px 16px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-option:hover {
            background: rgba(17, 63, 103, 0.1);
        }

        .chart-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .chart-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            position: relative;
            flex: 1 1 auto;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }

        .chart-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .chart-section h5 {
            font-size: 1.5rem;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .chart-content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: flex-start;
        }

        .chart-container-wrapper {
            flex: 1;
            min-width: 300px;
            max-width: 50%;
        }

        .table-container {
            flex: 1;
            min-width: 300px;
            max-width: 50%;
        }

        .chart-container {
            position: relative;
            height: auto;
            min-height: 200px;
            max-height: 250px;
        }

        .chart-container canvas {
            height: 230px !important;
            width: 100% !important;
        }

        .patient-count {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .revenue-label {
            font-size: 0.9rem;
            color: #666;
        }

        .period-controls {
            margin-top: 16px;
        }

        .dropdown-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .dropdown-inline {
            display: flex;
            align-items: center;
            gap: 6px;
            width: 150px;
        }

        .dropdown-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
        }

        .form-select-sm {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: var(--border-radius);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-percentage {
            font-weight: 500;
            color: var(--primary-color);
        }

        .table-responsive {
            border-radius: var(--border-radius);
            overflow-x: auto;
            max-height: 250px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 500;
        }

        .table tbody tr:hover {
            background: rgba(17, 63, 103, 0.05);
        }

        .geography-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 24px;
            position: relative;
        }

        .geography-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .geography-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .geography-title {
            font-size: 1.5rem;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .geography-filter {
            display: flex;
            gap: 10px;
        }

        .filter-btn-geo {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            padding: 8px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .filter-btn-geo.active,
        .filter-btn-geo:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .geography-content {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .map-container {
            flex: 1;
            min-width: 300px;
        }

        .location-list {
            flex: 1;
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .location-list::-webkit-scrollbar {
            width: 8px;
        }

        .location-list::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .location-list::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .location-list-header {
            font-size: 1.2rem;
            font-weight: 600;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .location-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition);
        }

        .location-item:hover {
            background: rgba(17, 63, 103, 0.05);
        }

        .location-name {
            font-weight: 500;
            color: var(--primary-color);
        }

        .location-address {
            font-size: 0.85rem;
            color: #666;
        }

        .location-stats {
            text-align: right;
        }

        .location-stats .patient-count {
            font-size: 1.2rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .location-stats .percentage {
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        .progress-bar {
            width: 100px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 4px;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary-gradient);
            transition: width var(--transition);
        }

        @media (max-width: 768px) {
            .geography-content {
                flex-direction: column;
            }

            .map-container,
            .location-list {
                min-width: 100%;
            }

            .map-container {
                height: 300px;
            }

            .location-list {
                max-height: 300px;
            }

            .chart-content {
                flex-direction: column;
            }

            .chart-container-wrapper,
            .table-container {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {

            .geography-title,
            .location-list-header {
                font-size: 1.2rem;
            }

            .filter-btn-geo {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            .branch-selector {
                flex-direction: column;
                align-items: flex-start;
            }

            .location-list {
                max-height: 250px;
            }
        }
    </style>
</head>

<body>
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
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('staff.dashboard') }}"
                    class="nav-link mb-1 {{ request()->routeIs(auth()->user()->role . '.dashboard') ? 'active' : '' }}">
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
                        <a href="{{ route('admin.currentPatients') }}"
                            class="nav-link py-1 small {{ request()->routeIs('admin.currentPatients') ? 'active' : '' }}">
                            <i class="fas fa-user me-2"></i>Current Patients
                        </a>
                        <a href="{{ route('admin.patientRecords') }}"
                            class="nav-link py-1 small {{ request()->routeIs('admin.patientRecords') ? 'active' : '' }}">
                            <i class="fas fa-file-medical me-2"></i>Patient Records
                        </a>
                    </div>
                </div>

                <div
                    class="mb-1 dropdown-menu-item {{ request()->is('appointments*') || request()->routeIs('allAppointments') ? 'open' : '' }}">
                    <a href="#appointmentsSubmenu" class="nav-link d-flex align-items-center" data-bs-toggle="collapse"
                        aria-expanded="{{ request()->is('appointments*') || request()->routeIs('allAppointments') ? 'true' : 'false' }}"
                        onclick="toggleDropdown(this)">
                        <span>
                            <i class="fas fa-calendar me-2"></i>
                            <span>Appointments</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div id="appointmentsSubmenu"
                        class="dropdown-submenu ms-3 collapse {{ request()->is('appointments*') || request()->routeIs('allAppointments') ? 'show' : '' }}">
                        <a href="{{ route('admin.appointments') }}"
                            class="nav-link py-1 small {{ request()->routeIs('admin.appointments') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i>Today's Appointments
                        </a>
                        <a href="{{ route('admin.allAppointments') }}"
                            class="nav-link py-1 small {{ request()->routeIs('admin.allAppointments') ? 'active' : '' }}">
                            <i class="fas fa-hourglass-half me-2"></i>All Appointments
                        </a>
                    </div>
                </div>

                <a href="{{ route('staffs') }}"
                    class="nav-link mb-1 {{ request()->routeIs('staffs') ? 'active' : '' }}">
                    <i class="fas fa-user-nurse me-2"></i>
                    <span>Staff</span>
                </a>

                <div class="mb-1 dropdown-menu-item {{ request()->is('admin/medication*') ? 'open' : '' }}">
                    <a href="#adminMedicationSubmenu" class="nav-link d-flex align-items-center"
                        data-bs-toggle="collapse"
                        aria-expanded="{{ request()->is('admin/medication*') ? 'true' : 'false' }}"
                        onclick="toggleDropdown(this)">
                        <span>
                            <i class="fas fa-prescription-bottle-medical me-2"></i>
                            <span>Medical Supply</span>
                        </span>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </a>
                    <div id="adminMedicationSubmenu"
                        class="dropdown-submenu ms-3 collapse {{ request()->is('admin/medication*') ? 'show' : '' }}">
                        <a href="{{ route('admin.inventory.medicines') }}"
                            class="nav-link py-1 small {{ request()->routeIs('admin.inventory.medicines') ? 'active' : '' }}">
                            <i class="fas fa-pills me-2"></i> Medicine
                        </a>
                        <a href="{{ route('admin.inventory.supplies') }}"
                            class="nav-link py-1 small {{ request()->routeIs('admin.inventory.supplies') ? 'active' : '' }}">
                            <i class="fas fa-boxes me-2"></i> Other Supply
                        </a>
                    </div>
                </div>

                <a href="{{ route('reports') }}"
                    class="nav-link mb-1 {{ request()->routeIs('reports') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-2"></i>
                    <span>Reports</span>
                </a>

                <a href="{{ route('admin.audit-logs') }}"
                    class="nav-link mb-1 {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                    <i class="fas fa-file-alt me-2"></i>
                    <span>Audit Logs</span>
                </a>
            </nav>
        </div>
    </div>

    <main class="content">
        <header class="main-header navbar navbar-expand-lg navbar-light sticky-top mb-4">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-btn d-md-none me-3" id="mobileMenuBtnHeader"
                        aria-label="Toggle sidebar menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="page-title mb-0">Reports</h4>
                </div>

                <div class="d-flex align-items-center header-right">
                    @include('partials.admin.notification')

                    @php
                        $user = Auth::user();
                        $avatar =
                            $user->admin && $user->admin->avatar_path
                                ? asset($user->admin->avatar_path)
                                : asset('img/adminProfile.jpg');
                    @endphp
                    <div class="dropdown user-profile">
                        <button class="btn btn-link p-1" data-bs-toggle="dropdown">
                            <img src="{{ $avatar }}" alt="Profile" class="rounded-circle" width="40"
                                height="40">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <li><a class="dropdown-item profile-dropdown-item" href="{{ route('adminProfile') }}">
                                    <i class="fas fa-user-circle me-2"></i>My Profile
                                </a></li>

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
            <div class="branch-selector">
                <label for="branchSelect" class="branch-selector-label">
                    <i class="fas fa-building"></i> Select Branch:
                </label>
                <form method="GET" id="branchForm" action="{{ route('reports') }}">
                    <input type="hidden" name="branch" id="branchInput"
                        value="{{ $selectedBranch ?? 'Combined' }}">
                    <div class="filter-dropdown">
                        <button type="button" class="filter-btn" id="branchSelect" aria-label="Select branch"
                            aria-expanded="false">
                            <span class="selected-option">{{ $selectedBranch ?? 'Combined' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown-menu" id="branchDropdownMenu">
                            <div class="filter-option" data-value="Combined">Combined</div>
                            <div class="filter-option" data-value="Santa Justina">Santa Justina</div>
                            <div class="filter-option" data-value="San Pedro">San Pedro</div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row g-4 chart-row">
                <!-- Monthly Patient Count Section -->
                <div class="col-12">
                    <div class="chart-section">
                        <h5>Monthly Patient Count</h5>
                        <div class="chart-content">
                            <!-- Chart and Metrics -->
                            <div class="chart-container-wrapper">
                                <div class="chart-container">
                                    <canvas id="barChart" aria-label="Monthly patient count chart"></canvas>
                                </div>
                                <div class="mt-3">
                                    <div class="patient-count">{{ $totalPatients ?? 145 }}</div>
                                    <div class="revenue-label">From April to May 2025</div>
                                    <div class="period-controls">
                                        <div class="dropdown-row">
                                            <div class="dropdown-inline">
                                                <label class="dropdown-label" for="fromMonth">From:</label>
                                                <select class="form-select form-select-sm" id="fromMonth">
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4" selected>April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </div>
                                            <div class="dropdown-inline">
                                                <label class="dropdown-label" for="toMonth">To:</label>
                                                <select class="form-select form-select-sm" id="toMonth">
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5" selected>May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                            </div>
                                            <div class="dropdown-inline">
                                                <label class="dropdown-label" for="yearSelect">Year:</label>
                                                <select class="form-select form-select-sm" id="yearSelect">
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2025" selected>2025</option>
                                                    <option value="2026">2026</option>
                                                    <option value="2027">2027</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- Inline Table for Patient Delivery Count -->
                            <div class="table-container">
                                <h6 class="mb-3">Patient Prenatal Records</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead style="background: var(--primary-gradient); color: white;">
                                            <tr>
                                                <th>Patient Name</th>
                                                <th>PDF File Name</th>
                                                <th>Visit Date</th>
                                                <th>Generated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($prenatalRecords as $record)
                                                <tr>
                                                    <td>{{ $record->patient->client->first_name ?? '' }}
                                                        {{ $record->patient->client->last_name ?? '' }}</td>
                                                    <td>
                                                        {{ $record->file_name ?? 'N/A' }}
                                                    </td>

                                                    <td>{{ optional($record->visit)->created_at ? $record->visit->created_at->format('Y-m-d') : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        @if (optional($record->visit)->staff)
                                                            {{ $record->visit->staff->first_name }}
                                                            {{ $record->visit->staff->last_name }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No prenatal records found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Delivery Status Breakdown Section -->
                <div class="col-12">
                    <div class="chart-section">
                        <h5>Delivery Records </h5>
                        <div class="chart-content">
                            <!-- Chart and Legend -->
                            <div class="chart-container-wrapper">
                                <div class="chart-container">
                                    <canvas id="pieChart" aria-label="Delivery status breakdown chart"></canvas>
                                </div>
                                <div class="mt-3">
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #FF6B6B;"></div>
                                        <span>Cancelled Delivery</span>
                                        <span class="legend-percentage" id="cancelledDelivery">30%</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color" style="background-color: #4CAF50;"></div>
                                        <span>Completed Delivery</span>
                                        <span class="legend-percentage" id="completedDelivery">70%</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Inline Table for Delivery Status Breakdown -->
                            <div class="table-container">
                                <h6 class="mb-3">Delivery Status Details</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead style="background: var(--primary-gradient); color: white;">
                                            <tr>
                                                <th>Patient Name</th>
                                                <th>PDF File Name</th>
                                                <th>Delivery Date</th>
                                                <th>Generated By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($babyPdfRecords as $record)
                                                @if ($record->baby_registration_id && $record->babyRegistration)
                                                    @php
                                                        $baby = $record->babyRegistration;
                                                        $delivery = $baby->delivery;
                                                        $staff = $delivery->staff ?? null;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $baby->baby_first_name ?? 'N/A' }}
                                                            {{ $baby->baby_last_name ?? '' }}
                                                        </td>
                                                        <td>
                                                            {{ $record->file_name ?? 'N/A' }}
                                                        </td>

                                                        <td>
                                                            {{ optional($delivery)->created_at ? $delivery->created_at->format('Y-m-d') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            @if ($staff)
                                                                {{ $staff->first_name ?? '' }}
                                                                {{ $staff->last_name ?? '' }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No baby PDF records found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="geography-section">
                <div class="geography-header">
                    <h5 class="geography-title">Patient Geographical Distribution</h5>
                    <div class="geography-filter">
                        <button class="filter-btn-geo active" onclick="filterByTimeframe('all')">All Time</button>
                        <button class="filter-btn-geo" onclick="filterByTimeframe('month')">This Month</button>
                        <button class="filter-btn-geo" onclick="filterByTimeframe('quarter')">This Quarter</button>
                    </div>
                </div>
                <div class="geography-content">
                    <div class="map-container">
                        <div id="map"
                            style="height: 400px; width: 100%; background: #f0f0f0; border-radius: 8px; position: relative;">
                            <div
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #666;">
                                <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>Map is loading...</p>
                            </div>
                        </div>
                    </div>
                    <div class="location-list">
                        <div class="location-list-header">Location Distribution</div>
                        @if ($locationData && $locationData->count() > 0)
                            @foreach ($locationData as $location)
                                <div class="location-item">
                                    <div class="location-info">
                                        <div class="location-name">{{ $location['name'] ?? 'Unknown Location' }}</div>
                                        <div class="location-address">{{ $location['address'] ?? 'Unknown Address' }}
                                        </div>
                                    </div>
                                    <div class="location-stats">
                                        <span class="patient-count">{{ $location['patient_count'] }}</span>
                                        <span class="percentage">{{ $location['percentage'] }}%</span>
                                        <div class="progress-bar">
                                            <div class="progress-fill"
                                                style="width: {{ $location['percentage'] }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="location-item">
                                <div class="location-info">
                                    <div class="location-name">No Data Available</div>
                                    <div class="location-address">Please add patient records</div>
                                </div>
                                <div class="location-stats">
                                    <span class="patient-count">0</span>
                                    <span class="percentage">0%</span>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 0%;"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const mapLocations = @json($mapLocations ?? []);
        const chartData = @json($chartData ?? []);
        let currentBarChart;
        let currentPieChart;
        let chartDataFromServer = {};

        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");
            const branchSelect = document.getElementById("branchSelect");
            const branchDropdownMenu = document.getElementById("branchDropdownMenu");
            const branchForm = document.getElementById("branchForm");
            const branchInput = document.getElementById("branchInput");

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

            branchSelect.addEventListener("click", function() {
                branchDropdownMenu.classList.toggle("show");
                branchSelect.setAttribute("aria-expanded", branchDropdownMenu.classList.contains("show"));
            });

            branchDropdownMenu.addEventListener("click", function(e) {
                if (e.target.classList.contains("filter-option")) {
                    const selectedBranch = e.target.getAttribute("data-value");
                    branchSelect.querySelector(".selected-option").textContent = selectedBranch;
                    branchInput.value = selectedBranch;
                    branchForm.submit();
                }
            });

            document.addEventListener("click", function(e) {
                if (!branchSelect.contains(e.target) && !branchDropdownMenu.contains(e.target)) {
                    branchDropdownMenu.classList.remove("show");
                    branchSelect.setAttribute("aria-expanded", "false");
                }
            });

            setTimeout(function() {
                if (typeof L !== 'undefined' && document.getElementById('map')) {
                    initializeMap();
                }
                initializeCharts();
            }, 300);

            function toggleDropdown(element) {
                const parent = element.parentElement;
                const isOpen = parent.classList.contains('open');
                document.querySelectorAll('.dropdown-menu-item').forEach(item => {
                    item.classList.remove('open');
                    item.querySelector('.dropdown-icon')?.classList.remove('rotate');
                });
                if (!isOpen) {
                    parent.classList.add('open');
                    element.querySelector('.dropdown-icon')?.classList.add('rotate');
                }
            }

            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown-menu-item')) {
                    document.querySelectorAll('.dropdown-menu-item').forEach(item => {
                        item.classList.remove('open');
                        item.querySelector('.dropdown-icon')?.classList.remove('rotate');
                    });
                }
            });

            document.querySelectorAll('.dropdown-submenu').forEach(submenu => {
                submenu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });
        });

        function initializeCharts() {
            if (typeof chartData !== 'undefined' && chartData) {
                chartDataFromServer = chartData;
                createBarChart();
                createPieChart();
                updatePatientCount();
                updateAgeBreakdown();
            } else {
                loadChartData();
            }
            setupChartControls();
        }

        function setupChartControls() {
            const fromMonth = document.getElementById('fromMonth');
            const toMonth = document.getElementById('toMonth');
            const yearSelect = document.getElementById('yearSelect');

            if (fromMonth && toMonth && yearSelect) {
                fromMonth.addEventListener('change', updateChartsFromControls);
                toMonth.addEventListener('change', updateChartsFromControls);
                yearSelect.addEventListener('change', updateChartsFromControls);
            }
        }

        function updateChartsFromControls() {
            const fromMonth = parseInt(document.getElementById('fromMonth').value);
            const toMonth = parseInt(document.getElementById('toMonth').value);
            const year = parseInt(document.getElementById('yearSelect').value);
            const branch = document.getElementById('branchInput')?.value || 'Combined';

            loadChartData(year, fromMonth, toMonth, branch);
        }



        async function loadChartData(year = null, fromMonth = null, toMonth = null, branch = null) {
            try {
                const params = new URLSearchParams();
                if (year) params.append('year', year);
                if (fromMonth) params.append('from_month', fromMonth);
                if (toMonth) params.append('to_month', toMonth);
                if (branch) params.append('branch', branch); // ✅ kasama na branch

                const response = await fetch(`/admin/reports/chart-data?${params.toString()}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                chartDataFromServer = data;

                createBarChart(data.monthly_counts, year || new Date().getFullYear());
                createPieChart(data.delivery_breakdown);
                updatePatientCount(data.range_total, data.period);
                updateAgeBreakdown(data.delivery_breakdown);

            } catch (error) {
                console.error('Error loading chart data:', error);
                createBarChart();
                createPieChart();
            }
        }


        function createBarChart(monthlyData = null, year = null) {
            const ctxBar = document.getElementById('barChart');
            if (!ctxBar) return;

            const ctx = ctxBar.getContext('2d');
            if (currentBarChart) {
                currentBarChart.destroy();
            }

            let chartDataArray = Array(12).fill(0);
            if (monthlyData) {
                chartDataArray = monthlyData;
            } else if (chartDataFromServer.monthly_counts && year) {
                chartDataArray = chartDataFromServer.monthly_counts[year] || Array(12).fill(0);
            } else if (chartDataFromServer.monthly_counts) {
                const currentYear = new Date().getFullYear();
                chartDataArray = chartDataFromServer.monthly_counts[currentYear] || Array(12).fill(0);
            }

            currentBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Patient Count',
                        data: chartDataArray,
                        backgroundColor: [
                            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FCEA2B', '#FF9FF3',
                            '#A8E6CF', '#FFD93D', '#6BCF7F', '#4D96FF', '#9B59B6', '#E67E22'
                        ],
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Patients: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 120,
                            grid: {
                                color: '#f0f0f0'
                            },
                            ticks: {
                                stepSize: 10,
                                color: '#666',
                                callback: function(value) {
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#666'
                            }
                        }
                    }
                }
            });
        }

        function createPieChart(deliveryData = null) {
            const ctxPie = document.getElementById('pieChart');
            if (!ctxPie) return;

            const ctx = ctxPie.getContext('2d');
            if (currentPieChart) {
                currentPieChart.destroy();
            }

            let pieData = [0, 0]; // [Cancelled, Completed]
            if (deliveryData) {
                pieData = [
                    deliveryData.cancelled_delivery,
                    deliveryData.completed_delivery
                ];
            } else if (chartDataFromServer.delivery_breakdown) {
                const db = chartDataFromServer.delivery_breakdown;
                pieData = [
                    db.cancelled_delivery,
                    db.completed_delivery
                ];
            }

            currentPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cancelled Delivery', 'Completed Delivery'],
                    datasets: [{
                        data: pieData,
                        backgroundColor: ['#FF6B6B', '#4CAF50'],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });

            // Update the legend with counts
            const cancelledEl = document.getElementById('cancelledDelivery');
            const completedEl = document.getElementById('completedDelivery');
            if (cancelledEl) cancelledEl.textContent = pieData[0];
            if (completedEl) completedEl.textContent = pieData[1];
        }


        function updatePatientCount(total = null, period = null) {
            const countElement = document.querySelector('.patient-count');
            const labelElement = document.querySelector('.revenue-label');

            if (countElement) {
                if (total !== null) {
                    countElement.textContent = total;
                } else if (chartDataFromServer.total_patients) {
                    countElement.textContent = chartDataFromServer.total_patients;
                }
            }

            if (labelElement && period) {
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];
                const fromMonthName = monthNames[period.from_month - 1];
                const toMonthName = monthNames[period.to_month - 1];
                labelElement.textContent = `From ${fromMonthName} to ${toMonthName} ${period.year}`;
            }
        }


        let map;
        let markersLayer;

        function initializeMap() {
            try {
                map = L.map('map').setView([13.4322, 123.5175], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'Buhi, Camarines Sur'
                }).addTo(map);
                markersLayer = L.layerGroup().addTo(map);

                if (typeof mapLocations !== 'undefined' && mapLocations.length > 0) {
                    geocodeAndDisplayLocations(mapLocations);
                } else {
                    loadLocationData('all');
                }
            } catch (error) {
                console.error('Map initialization error:', error);
                document.getElementById('map').innerHTML = `
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #666;">
                        <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>Map unavailable</p>
                        <small>Geographic data visualization</small>
                    </div>
                `;
            }
        }

        async function geocodeAndDisplayLocations(locations) {
            const geocodePromises = locations.map(location => geocodeAddress(location));
            try {
                const results = await Promise.allSettled(geocodePromises);
                results.forEach((result, index) => {
                    if (result.status === 'fulfilled' && result.value) {
                        addMarkerToMap(result.value, locations[index]);
                    } else {
                        console.warn(`Failed to geocode: ${locations[index].full_address}`);
                    }
                });

                if (markersLayer.getLayers().length > 0) {
                    map.fitBounds(markersLayer.getBounds(), {
                        padding: [20, 20]
                    });
                }
            } catch (error) {
                console.error('Error geocoding locations:', error);
            }
        }

        async function geocodeAddress(location) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location.full_address)}&limit=1`
                );
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                if (data && data.length > 0) {
                    return {
                        lat: parseFloat(data[0].lat),
                        lng: parseFloat(data[0].lon)
                    };
                }
                return null;
            } catch (error) {
                console.error(`Geocoding error for ${location.full_address}:`, error);
                return null;
            }
        }

        function addMarkerToMap(coordinates, locationData) {
            if (!coordinates || !coordinates.lat || !coordinates.lng) return;

            const markerSize = Math.max(8, Math.min(25, locationData.patient_count * 1.5));
            const marker = L.circleMarker([coordinates.lat, coordinates.lng], {
                radius: markerSize,
                fillColor: '#667eea',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            });

            marker.bindPopup(`
                <div style="text-align: center; min-width: 150px;">
                    <strong style="display: block; margin-bottom: 5px;">${locationData.name}</strong>
                    <small style="display: block; color: #666; margin-bottom: 5px;">${locationData.full_address}</small>
                    <span style="color: #667eea; font-weight: bold; font-size: 14px;">${locationData.patient_count} patients</span>
                </div>
            `);

            markersLayer.addLayer(marker);
        }

        async function loadLocationData(timeframe = 'all') {
            try {
                const response = await fetch(`/admin/reports/location-data?timeframe=${timeframe}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const locations = await response.json();
                markersLayer.clearLayers();
                if (locations && locations.length > 0) {
                    await geocodeAndDisplayLocations(locations);
                }
            } catch (error) {
                console.error('Error loading location data:', error);
            }
        }

        function filterByTimeframe(timeframe) {
            document.querySelectorAll('.filter-btn-geo').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            loadLocationData(timeframe);
        }

        @if (session('swal'))
            Swal.fire({
                icon: '{{ session('swal.icon') }}',
                title: '{{ session('swal.title') }}',
                text: '{{ session('swal.text') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: 'var(--primary-color)'
            });
        @endif
    </script>
</body>

</html>
