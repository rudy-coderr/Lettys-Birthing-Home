<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthing Home Care Tracker - Baby Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/imglogo.png') }}">
    <style>
        :root {
            --primary-color: #113F67;
            --primary-dark: #0d2f4d;
            --primary-gradient: linear-gradient(135deg, #113F67 0%, #0d2f4d 100%);
            --success-color: #10b981;
            --danger-color: #ef4444;
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
                top: 15px;
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
        .form-select {
            border-radius: var(--border-radius);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(17, 63, 103, 0.2);
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
            color: white;
            transition: var(--transition);
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
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            min-width: 350px;
            max-width: 90vw;
            max-height: 70vh;
            overflow-y: auto;
        }

        @media (max-width: 576px) {
            .notification-dropdown {
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

            .form-row {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }
        }

        .notification-dropdown li {
            list-style: none;
        }

        .notification-item {
            transition: var(--transition);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
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

        .stage {
            display: none;
        }

        .stage.active {
            display: block;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: var(--danger-color);
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

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
                            <i class="fas fa-check-circle me-2"></i>Complete Visit
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


    <main class="content">
        <header class="main-header navbar navbar-expand-lg navbar-light sticky-top mb-4">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-btn d-md-none me-3" id="mobileMenuBtnHeader"
                        aria-label="Toggle sidebar menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4 class="page-title mb-0">Baby Registration</h4>
                </div>
                <div class="d-flex align-items-center header-right">
                    <div class="dropdown me-2">
                        <button class="btn btn-link p-1 position-relative" data-bs-toggle="dropdown"
                            aria-label="Notifications">
                            <i class="fas fa-bell" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                            <span
                                class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill">3
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
                    @php
                        $staff = Auth::user()->staff;
                        $avatar = $staff && $staff->avatar_path ? asset($staff->avatar_path) : asset('img/adminProfile.jpg');
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
           
            <form id="babyRegistrationForm" class="needs-validation" novalidate method="POST"
                action="{{ route('storeBabyRegistration', $delivery->id) }}">
                @csrf
                <div class="stage active" id="registrationStage">
                    <div class="form-card">
                        <div class="form-header">
                            <h5><i class="fas fa-clipboard-list me-2"></i>Baby Registration</h5>
                        </div>

                        {{-- Baby Information --}}
                        <div class="form-section">
                            <div class="form-section-title"><i class="fas fa-file-alt me-2"></i>Baby Information</div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="baby_first_name">First Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="baby_first_name"
                                        name="baby_first_name" placeholder="First Name" required>
                                    <div class="invalid-feedback">First Name is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="baby_middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="baby_middle_name"
                                        name="baby_middle_name" placeholder="Middle Name">
                                </div>
                                <div class="form-group">
                                    <label for="baby_last_name">Last Name <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="baby_last_name"
                                        name="baby_last_name" placeholder="Last Name" required>
                                    <div class="invalid-feedback">Last Name is required</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sex">Sex <span class="required">*</span></label>
                                    <select class="form-select" id="sex" name="sex" required>
                                        <option value="" disabled selected>Select sex</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <div class="invalid-feedback">Sex is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth <span class="required">*</span></label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                        required>
                                    <div class="invalid-feedback">Date of Birth is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="time_of_birth">Time of Birth <span class="required">*</span></label>
                                    <input type="time" class="form-control" id="time_of_birth" name="time_of_birth"
                                        required>
                                    <div class="invalid-feedback">Time of Birth is required</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="place_of_birth">Place of Birth <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="place_of_birth"
                                        name="place_of_birth" placeholder="Place of Birth" required>
                                    <div class="invalid-feedback">Place of Birth is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="type_of_birth">Type of Birth <span class="required">*</span></label>
                                    <select class="form-select" id="type_of_birth" name="type_of_birth" required>
                                        <option value="" disabled selected>Select type</option>
                                        <option value="single">Single</option>
                                        <option value="twin">Twin</option>
                                        <option value="triplet">Triplet, etc.</option>
                                    </select>
                                    <div class="invalid-feedback">Type of Birth is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="birth_order">Birth Order</label>
                                    <input type="text" class="form-control" id="birth_order" name="birth_order"
                                        placeholder="Birth Order">
                                </div>
                                <div class="form-group">
                                    <label for="weight_at_birth">Weight at Birth <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="weight_at_birth"
                                        name="weight_at_birth" placeholder="Weight in grams" required>
                                    <div class="invalid-feedback">Weight at Birth is required</div>
                                </div>
                            </div>
                        </div>

                        {{-- Mother’s Info --}}
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="fas fa-user me-2"></i>Mother's Information
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mother_maiden_first_name">Maiden First Name <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="mother_maiden_first_name"
                                        name="mother_maiden_first_name"
                                        value="{{ old('mother_maiden_first_name', $motherInfo['first_name'] ?? '') }}"
                                        placeholder="First Name" required>
                                    <div class="invalid-feedback">Maiden First Name is required</div>
                                </div>

                                <div class="form-group">
                                    <label for="mother_maiden_middle_name">Maiden Middle Name</label>
                                    <input type="text" class="form-control" id="mother_maiden_middle_name"
                                        name="mother_maiden_middle_name" placeholder="Middle Name">
                                </div>

                                <div class="form-group">
                                    <label for="mother_maiden_last_name">Maiden Last Name <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="mother_maiden_last_name"
                                        name="mother_maiden_last_name"
                                        value="{{ old('mother_maiden_last_name', $motherInfo['last_name'] ?? '') }}"
                                        placeholder="Last Name" required>
                                    <div class="invalid-feedback">Maiden Last Name is required</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mother_citizenship">Citizenship <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="mother_citizenship"
                                        name="mother_citizenship" placeholder="Citizenship" required>
                                    <div class="invalid-feedback">Citizenship is required</div>
                                </div>

                                <div class="form-group">
                                    <label for="mother_religion">Religion</label>
                                    <input type="text" class="form-control" id="mother_religion"
                                        name="mother_religion" value="{{ old('mother_religion') }}"
                                        placeholder="Religion">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mother_total_children_alive">Total No. of Children Born Alive <span
                                            class="required">*</span></label>
                                    <input type="number" class="form-control" id="mother_total_children_alive"
                                        name="mother_total_children_alive" placeholder="Number" min="0"
                                        required>
                                    <div class="invalid-feedback">Total No. of Children is required</div>
                                </div>

                                <div class="form-group">
                                    <label for="mother_children_still_living">No. of Children Still Living <span
                                            class="required">*</span></label>
                                    <input type="number" class="form-control" id="mother_children_still_living"
                                        name="mother_children_still_living" placeholder="Number" min="0"
                                        required>
                                    <div class="invalid-feedback">No. of Children Still Living is required</div>
                                </div>

                                <div class="form-group">
                                    <label for="mother_children_deceased">No. of Children Born Alive but Now Deceased</label>
                                    <input type="number" class="form-control" id="mother_children_deceased"
                                        name="mother_children_deceased" placeholder="Number" min="0">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mother_occupation">Occupation <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="mother_occupation"
                                        name="mother_occupation" placeholder="Occupation" required>
                                    <div class="invalid-feedback">Occupation is required</div>
                                </div>

                                <div class="form-group">
                                    <label for="mother_age">Age <span class="required">*</span></label>
                                    <input type="number" class="form-control" id="mother_age" name="mother_age"
                                        value="{{ old('mother_age', $motherInfo['age'] ?? '') }}" placeholder="Age"
                                        min="0" required>
                                    <div class="invalid-feedback">Age is required</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mother_address">Address <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="mother_address"
                                        name="mother_address"
                                        value="{{ old('mother_address', $motherInfo['full_address'] ?? '') }}"
                                        placeholder="Village, City/Municipality, Province" required>
                                    <div class="invalid-feedback">Address is required</div>
                                </div>
                            </div>
                        </div>

                        {{-- Father’s Info --}}
                        <div class="form-section">
                            <div class="form-section-title"><i class="fas fa-user-tie me-2"></i>Father's Information
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="spouse_fname">First Name <span class="required">*</span></label>
                                    <input type="text" name="spouse_fname"
                                        value="{{ old('spouse_fname', optional($delivery->patient)->spouse_fname) }}"
                                        placeholder="First Name" class="form-control" id="spouse_fname" required>
                                    <div class="invalid-feedback">First Name is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="father_middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="father_middle_name"
                                        name="father_middle_name" placeholder="Middle Name">
                                </div>
                                <div class="form-group">
                                    <label for="spouse_lname">Last Name <span class="required">*</span></label>
                                    <input type="text" name="spouse_lname"
                                        value="{{ old('spouse_lname', optional($delivery->patient)->spouse_lname) }}"
                                        placeholder="Last Name" class="form-control" id="spouse_lname" required>
                                    <div class="invalid-feedback">Last Name is required</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="father_citizenship">Citizenship <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="father_citizenship"
                                        name="father_citizenship" placeholder="Citizenship" required>
                                    <div class="invalid-feedback">Citizenship is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="father_religion">Religion</label>
                                    <input type="text" class="form-control" id="father_religion"
                                        name="father_religion" placeholder="Religion">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="father_occupation">Occupation <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="father_occupation"
                                        name="father_occupation" placeholder="Occupation" required>
                                    <div class="invalid-feedback">Occupation is required</div>
                                </div>
                                <div class="form-group">
                                    <label for="father_age">Age <span class="required">*</span></label>
                                    <input type="number" class="form-control" id="father_age" name="father_age"
                                        placeholder="Age" min="0" required>
                                    <div class="invalid-feedback">Age is required</div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="father_address">Address <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="father_address"
                                        name="father_address" placeholder="Address" required>
                                    <div class="invalid-feedback">Address is required</div>
                                </div>
                            </div>
                        </div>

                        {{-- Marriage Info --}}
                        <div class="form-section">
                            <div class="form-section-title"><i class="fas fa-ring me-2"></i>Marriage Information</div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="marriage_date">Date of Marriage <span
                                            class="required">*</span></label>
                                    <input type="date" class="form-control" id="marriage_date"
                                        name="marriage_date" required>
                                    <div class="invalid-feedback">Date of Marriage is required</div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="marriage_place">Place of Marriage <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="marriage_place"
                                        name="marriage_place" placeholder="Enter Place" required>
                                    <div class="invalid-feedback">Place of Marriage is required</div>
                                </div>
                            </div>
                        </div>

                        {{-- Additional Info --}}
                        <div class="form-section">
                            <div class="form-section-title"><i class="fas fa-user-md me-2"></i>Additional Information
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="birth_attendant">Birth Attendant <span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="birth_attendant"
                                        name="birth_attendant" placeholder="Name" required>
                                    <div class="invalid-feedback">Birth Attendant is required</div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="form-section form-actions">
                            <button type="submit" class="btnn">
                                <i class="fas fa-save me-2"></i>Save Record
                            </button>
                            <button type="button" class="btnn" id="registrationComplete">
                                <i class="fas fa-check-circle me-2"></i>Complete All Registration Requirements
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");
            const btnOpen = document.getElementById("mobileMenuBtnHeader");
            const btnClose = document.getElementById("mobileMenuBtnSidebar");
            const form = document.getElementById("babyRegistrationForm");
            const completeButton = document.getElementById("registrationComplete");
            const requiredFields = document.querySelectorAll(
                "#registrationStage input[required], #registrationStage select[required]");

            btnOpen.addEventListener("click", () => {
                sidebar.classList.add("mobile-show");
                sidebarOverlay.classList.add("show");
            });
            btnClose.addEventListener("click", () => {
                sidebar.classList.remove("mobile-show");
                sidebarOverlay.classList.remove("show");
            });
            sidebarOverlay.addEventListener("click", () => {
                sidebar.classList.remove("mobile-show");
                sidebarOverlay.classList.remove("show");
            });

            requiredFields.forEach(field => {
                field.addEventListener("input", () => {
                    const allFilled = Array.from(requiredFields).every(f => f.value);
                    completeButton.disabled = !allFilled;
                });
            });

            form.addEventListener("submit", (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.classList.add("was-validated");
                }
            });

            completeButton.addEventListener("click", () => {
                if (form.checkValidity()) {
                    Swal.fire({
                        title: 'Registration Completed',
                        text: 'All registration requirements have been completed (frontend-only).',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    form.classList.add("was-validated");
                }
            });

            function toggleDropdown(element) {
                const icon = element.querySelector('.dropdown-icon');
                icon.classList.toggle('rotate');
                const submenu = element.nextElementSibling;
                submenu.classList.toggle('show');
            }
        });
    </script>
</body>

</html>