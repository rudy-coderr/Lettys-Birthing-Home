<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Appointment - Letty's Birthing Home</title>
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

        .form-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .form-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-section {
            padding: 1.5rem;
            margin-bottom: 24px;
        }

        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-control,
        .form-select,
        .form-textarea {
            border-radius: var(--border-radius);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(17, 63, 103, 0.2);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            width: 100%;
        }

        .required {
            color: var(--danger-color);
        }

        .btnn {
            background: var(--primary-gradient);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btnn:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .back-button {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            background: rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-4px);
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
                left: 5% !important;
                right: 5% !important;
                width: 90% !important;
                max-width: 250px !important;
                min-width: unset !important;
                margin: 0 auto;
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                z-index: 1050;
                max-height: 70vh;
                overflow-y: auto;

            }
        }

        .notification-dropdown li {
            list-style: none;
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
            background: var(--danger-color) !important;
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

        @media (max-width: 576px) {
            .form-row {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
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

    <!-- Main Content -->
    <main class="content">
        <header class="main-header navbar navbar-expand-lg navbar-light sticky-top mb-4">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-btn d-md-none me-3" id="mobileMenuBtnHeader"
                        aria-label="Toggle sidebar menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="page-title mb-0">Add Appointment</h4>
                </div>

                <div class="d-flex align-items-center header-right">
                    <!-- Notification Dropdown -->
                    <div class="dropdown me-2">
                        <button class="btn btn-link p-1 position-relative" data-bs-toggle="dropdown"
                            aria-label="Notifications">
                            <i class="fas fa-bell" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                            <span
                                class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill">
                                {{ $unreadNotifications ?? 3 }}
                                <span class="visually-hidden">unread notifications</span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <li class="p-3 border-bottom">
                                <h6 class="mb-0">Notifications</h6>
                            </li>
                            <li class="notification-item p-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                                        <div>
                                            <p class="mb-0">New appointment scheduled for today at 2:00 PM.</p>
                                            <small class="text-muted">5 minutes ago</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-item p-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                                        <div>
                                            <p class="mb-0">Patient record updated for Jane Doe.</p>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-item p-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock me-2 text-danger"></i>
                                        <div>
                                            <p class="mb-0">Pending appointment approval needed.</p>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="p-3 text-center">
                                <a href="#" class="text-primary text-decoration-none">View all notifications</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Profile Dropdown -->
                    @php
                        $user = Auth::user();
                        $avatar =
                            $user->role === 'admin' && $user->admin && $user->admin->avatar_path
                                ? asset($user->admin->avatar_path)
                                : ($user->role === 'staff' && $user->staff && $user->staff->avatar_path
                                    ? asset($user->staff->avatar_path)
                                    : asset('img/adminProfile.jpg'));
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


        <div class="container-fluid main-content" >
            <div class="form-card" id="appointmentSection" >
                <div class="form-header">
                    <h5>
                        <i class="fas fa-calendar-plus me-2"></i>
                        Add New Appointment
                    </h5>
                    <div class="header-actions">
                        <a href="{{ route('todaysAppointments') }}" class="back-button">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.storeAppointment') }}" method="POST">
                    @csrf
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-user-circle me-2"></i>
                            Patient Information
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="firstName" name="first_name"
                                    placeholder="Enter First Name" required>
                                <div class="invalid-feedback">Please enter a valid first name.</div>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name <span class="required">*</span></label>
                                <input type="text" class="form-control" id="lastName" name="last_name"
                                    placeholder="Enter Last Name" required>
                                <div class="invalid-feedback">Please enter a valid last name.</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number <span class="required">*</span></label>
                                <input type="tel" class="form-control" id="phoneNumber" name="client_phone"
                                    placeholder="09123456789" pattern="09[0-9]{9}" required>
                                <div class="invalid-feedback">Please enter a valid phone number starting with 09.</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-calendar-check me-2"></i>
                            Appointment Details
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="appointmentDate">Appointment Date <span class="required">*</span></label>
                                <input type="date" class="form-control" id="appointmentDate"
                                    name="appointment_date" required>
                                <div class="invalid-feedback">Please select a valid date.</div>
                            </div>
                            <div class="form-group">
                                <label for="appointmentTime">Appointment Time <span class="required">*</span></label>
                                <input type="time" class="form-control" id="appointmentTime"
                                    name="appointment_time" required>
                                <div class="invalid-feedback">Please select a valid time.</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="branch">Branch <span class="required">*</span></label>
                                <select class="form-select" id="branch" name="branch" required>
                                    <option value="" disabled selected>Select Branch</option>
                                    <option value="Sta. Justina">Sta. Justina</option>
                                    <option value="San Pedro">San Pedro</option>
                                </select>
                                <div class="invalid-feedback">Please select a branch.</div>
                            </div>
                            <div class="form-group">
                                <label for="reason">Reason <span class="required">*</span></label>
                                <textarea class="form-textarea" id="reason" name="reason" placeholder="Enter Reason for Appointment" required></textarea>
                                <div class="invalid-feedback">Please enter a valid reason.</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-section form-actions">
                        <button type="submit" class="btnn btn-primary">
                            <i class="fas fa-save me-2"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="emergency-container">
            @include('partials.emergencyModal')
        </div>
    </main>

    <!-- Scripts -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('script/emergency.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");

            // Open sidebar
            btnOpen.addEventListener("click", function() {
                sidebar.classList.add("mobile-show");
                sidebarOverlay.classList.add("show");
            });

            // Close sidebar
            btnClose.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                sidebarOverlay.classList.remove("show");
            });

            // Close when clicking overlay
            sidebarOverlay.addEventListener("click", function() {
                sidebar.classList.remove("mobile-show");
                this.classList.remove("show");
            });

            // Form validation
            const form = document.getElementById("addAppointmentForm");
            form.addEventListener("submit", function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            });
        });

        function toggleDropdown(element) {
            const icon = element.querySelector('.dropdown-icon');
            icon.classList.toggle('rotate');
        }
    </script>
</body>

</html>
