<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Letty's Birthing Home</title>
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
        .appointments-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .appointments-header {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .appointments-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .add-appointment-btn {
            background: linear-gradient(135deg,rgb(85, 128, 86),rgb(63, 126, 66));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .add-appointment-btn:hover {
            background: linear-gradient(135deg, rgb(47, 184, 52), rgb(37, 167, 41));
        }

        /* Tab Styles */
        .tab-container {
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        .tab-nav {
            display: flex;
            padding: 0 25px;
            gap: 0;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 15px 25px;
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }
        .tab-btn:hover {
            color: #2c3e50;
            background: rgba(76, 175, 80, 0.05);
        }
        .tab-btn.active {
            color: #2c3e50;
            border-bottom-color: #4CAF50;
            background: white;
        }
        .tab-badge {
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-left: 8px;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }

        .table-container {
            overflow-x: auto;
        }
        .appointments-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }
        .appointments-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px;
            padding: 15px 12px;
            border-bottom: 2px solid #e9ecef;
            text-align: left;
            white-space: nowrap;
        }
        .appointments-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #495057;
            font-size: 13px;
            vertical-align: middle;
        }
        .appointments-table tbody tr {
            transition: all 0.2s ease;
        }
        .appointments-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .patient-id {
            font-weight: 600;
            color: #2c3e50;
        }
        .patient-name {
            font-weight: 500;
            color: #2c3e50;
        }
        .schedule-info {
            color: #495057;
            font-weight: 500;
        }
        .service-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }
        .service-full {
            background: #4CAF50;
            color: white;
        }
        .service-partial {
            background: #2196F3;
            color: white;
        }
        .service-consult {
            background: #FF9800;
            color: white;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }
        .status-pending {
            background: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEAA7;
        }
        .status-urgent {
            background: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
        .actions-cell {
            text-align: center;
            white-space: nowrap;
        }
        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.2s ease;
        }
        .edit-btn {
            background: #e3f2fd;
            color: #1976d2;
        }
        .edit-btn:hover {
            background: #1976d2;
            color: white;
        }
        .approve-btn {
            background: #e8f5e9;
            color:rgb(100, 192, 192);
        }
        .approve-btn:hover {
            background:rgb(48, 100, 100);
            color: white;
        }
        .delete-btn {
            background: #ffebee;
            color: #d32f2f;
        }
        .delete-btn:hover {
            background: #d32f2f;
            color: white;
        }
        .search-filter-section {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
            max-width: 400px;
        }
        .search-box input {
            width: 100%;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 40px 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .search-box input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        /* Filter Dropdown Styles */
        .filter-dropdown {
            position: relative;
            min-width: 120px;
        }
        .filter-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            color: #495057;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }
        .filter-btn:hover, .filter-btn.active {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        .filter-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 250px;
            overflow-y: auto;
            display: none;
        }
        .filter-dropdown-menu.show {
            display: block;
        }
        .filter-option {
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #495057;
        }
        .filter-option:hover {
            background-color: #f8f9fa;
        }
        .filter-option.selected {
            background-color: #e8f5e8;
            color: #2d5a3d;
            font-weight: 500;
        }
        .filter-option:last-child {
            border-bottom: none;
        }
        .clear-filters-btn {
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .clear-filters-btn:hover {
            background: #5a6268;
        }
        .filter-count {
            background: #4CAF50;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-left: 5px;
        }
        
        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-style: italic;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 200px;
            }
             .header {
                left: 0;
                width: 100%;
            }
        }
        
        @media (max-width: 992px) {
            .appointments-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            .search-filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                max-width: none;
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
            }
            .header h4 {
                font-size: 16px;
            }
            .main-content {
                padding: 15px;
            }
            .appointments-header {
                padding: 20px;
            }
            .search-filter-section {
                padding: 20px;
            }
            .appointments-table th,
            .appointments-table td {
                padding: 10px 8px;
                font-size: 12px;
            }
            .service-badge, .status-badge {
                font-size: 10px;
                padding: 4px 8px;
            }
            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }
            .tab-nav {
                padding: 0 15px;
            }
            .tab-btn {
                padding: 12px 15px;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 10px;
            }
            .appointments-header {
                padding: 15px;
            }
            .search-filter-section {
                padding: 15px;
            }
            .appointments-table th,
            .appointments-table td {
                padding: 8px 6px;
                font-size: 11px;
            }
            .add-appointment-btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            .tab-nav {
                padding: 0 10px;
            }
            .tab-btn {
                padding: 10px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div class="sidebar" id="sidebar">
        <div class="logo-section">
            <div class="logo">
                 <img src="{{ asset('img/imglogo.png') }}" alt="Logo">
            </div>
            <h5>Letty's Birthing Home</h5>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboards') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="{{ route('patients') }}"><i class="fas fa-users"></i> Patients</a>
            <a href="{{ route('appointments') }}" class="active"><i class="fas fa-calendar"></i> Appointments</a>
            <a href="{{ route('staffs') }} " ><i class="fas fa-user-nurse"></i> Staff</a>
            <a href="{{ route('medications') }}" ><i class="fas fa-pills"></i> Medication</a>
            <a href="{{ route('reports') }}"><i class="fas fa-file-alt"></i> Reports</a>
             <a href="{{ route('settings') }}"><i class="fas fa-cog"></i> Settings</a>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Appointments Management</h4>
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
            <div class="appointments-card">
                <div class="appointments-header">
                    <h5>Appointments Management</h5>
                    <button class="add-appointment-btn">
                        <i class="fas fa-plus"></i> Add Appointment
                    </button>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="switchTab('all')">
                            All Appointments
                              <span class="tab-badge">4</span>
                        </button>
                        <button class="tab-btn" onclick="switchTab('pending')">
                            Pending Appointments
                            <span class="tab-badge">3</span>
                        </button>
                    </div>
                </div>

                <!-- All Appointments Tab -->
                <div id="allTab" class="tab-content active">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputAll" placeholder="Search appointments...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('all')">
                                <span>Filter</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountAll" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownAll">
                                <div class="filter-option selected" onclick="filterAppointments('all', 'all')">All Services</div>
                                <div class="filter-option" onclick="filterAppointments('all', 'full')">Full Service</div>
                                <div class="filter-option" onclick="filterAppointments('all', 'partial')">Partial Service</div>
                                <div class="filter-option" onclick="filterAppointments('all', 'consult')">Consult Only</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnAll" style="display: none;" onclick="clearFilters('all')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="appointments-table" id="appointmentsTableAll">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Schedule</th>
                                    <th>Service Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-service="full">
                                    <td class="patient-id">PT001</td>
                                    <td class="patient-name">Andeng Remocopillo</td>
                                    <td class="schedule-info">May 16, 2026, 8:00am</td>
                                    <td><span class="service-badge service-full">Full Service</span></td>
                                    <td class="actions-cell">
                                       <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-service="partial">
                                    <td class="patient-id">PT002</td>
                                    <td class="patient-name">Maria Santos</td>
                                    <td class="schedule-info">May 16, 2026, 10:00am</td>
                                    <td><span class="service-badge service-partial">Partial Service</span></td>
                                    <td class="actions-cell">
                                       <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-service="consult">
                                    <td class="patient-id">PT003</td>
                                    <td class="patient-name">Rosa dela Cruz</td>
                                    <td class="schedule-info">May 16, 2026, 8:00am</td>
                                    <td><span class="service-badge service-consult">Consult Only</span></td>
                                    <td class="actions-cell">
                                         <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-service="partial">
                                    <td class="patient-id">PT004</td>
                                    <td class="patient-name">Carmen Reyes</td>
                                    <td class="schedule-info">May 20, 2026, 10:00am</td>
                                    <td><span class="service-badge service-partial">Partial Service</span></td>
                                    <td class="actions-cell">
                                         <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pending Appointments Tab -->
                <div id="pendingTab" class="tab-content">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputPending" placeholder="Search pending appointments...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('pending')">
                                <span>Filter</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountPending" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownPending">
                                <div class="filter-option selected" onclick="filterAppointments('pending', 'all')">All Status</div>
                                <div class="filter-option" onclick="filterAppointments('pending', 'pending')">Pending</div>
                                <div class="filter-option" onclick="filterAppointments('pending', 'urgent')">Urgent</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnPending" style="display: none;" onclick="clearFilters('pending')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="appointments-table" id="appointmentsTablePending">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Requested Date</th>
                                    <th>Service Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-status="pending" data-service="full">
                                    <td class="patient-id">PT005</td>
                                    <td class="patient-name">Luz Mercado</td>
                                    <td class="schedule-info">June 18, 2025, 9:00am</td>
                                    <td><span class="service-badge service-full">Full Service</span></td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Decline">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="urgent" data-service="consult">
                                    <td class="patient-id">PT006</td>
                                    <td class="patient-name">Elena Garcia</td>
                                    <td class="schedule-info">June 17, 2025, 2:00pm</td>
                                    <td><span class="service-badge service-consult">Consult Only</span></td>
                                    <td><span class="status-badge status-urgent">Urgent</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Decline">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="pending" data-service="partial">
                                    <td class="patient-id">PT007</td>
                                    <td class="patient-name">Anna Morales</td>
                                    <td class="schedule-info">June 19, 2025, 11:00am</td>
                                    <td><span class="service-badge service-partial">Partial Service</span></td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn approve-btn" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Decline">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }

        // Tab switching functionality
        function switchTab(tabName) {
            // Remove active class from all tabs and buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab and button
            if (tabName === 'all') {
                document.querySelector('.tab-btn').classList.add('active');
                document.getElementById('allTab').classList.add('active');
            } else if (tabName === 'pending') {
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
                document.getElementById('pendingTab').classList.add('active');
            }
        }

        // Filter functionality
        function toggleFilter(tabType) {
            const dropdown = document.getElementById(`filterDropdown${tabType.charAt(0).toUpperCase() + tabType.slice(1)}`);
            dropdown.classList.toggle('show');
        }

        function filterAppointments(tabType, filterType) {
            const tableId = tabType === 'all' ? 'appointmentsTableAll' : 'appointmentsTablePending';
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            const filterOptions = document.querySelectorAll(`#filterDropdown${tabType.charAt(0).toUpperCase() + tabType.slice(1)} .filter-option`);
            const clearBtn = document.getElementById(`clearFiltersBtn${tabType.charAt(0).toUpperCase() + tabType.slice(1)}`);
            
            // Update selected option
            filterOptions.forEach(option => option.classList.remove('selected'));
            event.target.classList.add('selected');
            
            // Filter rows based on tab type
            rows.forEach(row => {
                let shouldShow = false;
                
                if (tabType === 'all') {
                    // Filter by service type for All Appointments
                    if (filterType === 'all' || row.dataset.service === filterType) {
                        shouldShow = true;
                    }
                } else if (tabType === 'pending') {
                    // Filter by status for Pending Appointments
                    if (filterType === 'all' || row.dataset.status === filterType) {
                        shouldShow = true;
                    }
                }
                
                row.style.display = shouldShow ? '' : 'none';
            });
            
            // Show/hide clear button
            if (filterType !== 'all') {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
            
            // Close dropdown
            document.getElementById(`filterDropdown${tabType.charAt(0).toUpperCase() + tabType.slice(1)}`).classList.remove('show');
        }

        function clearFilters(tabType) {
            const tableId = tabType === 'all' ? 'appointmentsTableAll' : 'appointmentsTablePending';
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            const filterOptions = document.querySelectorAll(`#filterDropdown${tabType.charAt(0).toUpperCase() + tabType.slice(1)} .filter-option`);
            const clearBtn = document.getElementById(`clearFiltersBtn${tabType.charAt(0).toUpperCase() + tabType.slice(1)}`);
            
            // Show all rows
            rows.forEach(row => row.style.display = '');
            
            // Reset filter selection
            filterOptions.forEach(option => option.classList.remove('selected'));
            filterOptions[0].classList.add('selected');
            
            // Hide clear button
            clearBtn.style.display = 'none';
        }

        // Search functionality for All Appointments
        document.getElementById('searchInputAll').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTableAll tbody tr');
            
            rows.forEach(row => {
                const patientId = row.querySelector('.patient-id').textContent.toLowerCase();
                const patientName = row.querySelector('.patient-name').textContent.toLowerCase();
                const schedule = row.querySelector('.schedule-info').textContent.toLowerCase();
                
                if (patientId.includes(searchTerm) || patientName.includes(searchTerm) || schedule.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Search functionality for Pending Appointments
        document.getElementById('searchInputPending').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTablePending tbody tr');
            
            rows.forEach(row => {
                const patientId = row.querySelector('.patient-id').textContent.toLowerCase();
                const patientName = row.querySelector('.patient-name').textContent.toLowerCase();
                const schedule = row.querySelector('.schedule-info').textContent.toLowerCase();
                
                if (patientId.includes(searchTerm) || patientName.includes(searchTerm) || schedule.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdownAll = document.getElementById('filterDropdownAll');
            const dropdownPending = document.getElementById('filterDropdownPending');
            const filterBtns = document.querySelectorAll('.filter-btn');
            
            let clickedFilterBtn = false;
            filterBtns.forEach(btn => {
                if (btn.contains(event.target)) {
                    clickedFilterBtn = true;
                }
            });
            
            if (!clickedFilterBtn) {
                dropdownAll.classList.remove('show');
                dropdownPending.classList.remove('show');
            }
        });

        // Action button handlers
        document.addEventListener('click', function(event) {
            if (event.target.closest('.approve-btn')) {
                const row = event.target.closest('tr');
                const patientName = row.querySelector('.patient-name').textContent;
                if (confirm(`Approve appointment for ${patientName}?`)) {
                    // Here you would typically make an API call to approve the appointment
                    alert('Appointment approved successfully!');
                    // You might want to move this row to the "All Appointments" tab
                    // or remove it from pending and update the badge count
                }
            }
            
            if (event.target.closest('.delete-btn')) {
                const row = event.target.closest('tr');
                const patientName = row.querySelector('.patient-name').textContent;
                const action = row.closest('#pendingTab') ? 'decline' : 'delete';
                if (confirm(`${action.charAt(0).toUpperCase() + action.slice(1)} appointment for ${patientName}?`)) {
                    row.remove();
                    alert(`Appointment ${action}d successfully!`);
                }
            }
            
            if (event.target.closest('.edit-btn')) {
                const row = event.target.closest('tr');
                const patientName = row.querySelector('.patient-name').textContent;
                alert(`Edit appointment for ${patientName}`);
                // Here you would typically open an edit modal or navigate to edit page
            }
        });
    </script>
</body>
</html>