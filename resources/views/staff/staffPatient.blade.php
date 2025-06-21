<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - Letty's Birthing Home</title>
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
        
        .patients-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .patients-header {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .patients-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .add-patient-btn {
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
        .add-patient-btn:hover {
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
        
        /* Tab Content */
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
            min-width: 140px;
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

        .table-container {
            overflow-x: auto;
        }
        .patients-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }
        .patients-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px;
            padding: 15px 12px;
            border-bottom: 2px solid #e9ecef;
            text-align: left;
            white-space: nowrap;
        }
        .patients-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #495057;
            font-size: 13px;
            vertical-align: middle;
        }
        .patients-table tbody tr {
            transition: all 0.2s ease;
        }
        .patients-table tbody tr:hover {
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
        .patient-address {
            color: #495057;
        }
        .patient-age {
            color: #495057;
            font-weight: 500;
        }
        .patient-date {
            color: #6c757d;
            font-size: 12px;
        }
        .patient-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }
        .status-current {
            background: #4CAF50;
            color: white;
        }
        .status-discharged {
            background: #e8f5e8;
            color: #2d5a3d;
        }
        .status-completed {
            background: #e3f2fd;
            color: #1976d2;
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
        .delete-btn {
            background: #ffebee;
            color: #d32f2f;
        }
        .delete-btn:hover {
            background: #d32f2f;
            color: white;
        }
        .view-btn {
            background: #e8f5e8;
            color: #2d5a3d;
        }
        .view-btn:hover {
            background: #2d5a3d;
            color: white;
        }
        .archive-btn {
            background: #fff3e0;
            color: #f57c00;
        }
        .archive-btn:hover {
            background: #f57c00;
            color: white;
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
                left: 200px;
            }
        }
        
        @media (max-width: 992px) {
            .patients-header {
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
            .header {
                left: 0;
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
            .patients-header {
                padding: 20px;
            }
            .search-filter-section {
                padding: 20px;
            }
            .patients-table th,
            .patients-table td {
                padding: 10px 8px;
                font-size: 12px;
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
            .patients-header {
                padding: 15px;
            }
            .search-filter-section {
                padding: 15px;
            }
            .patients-table th,
            .patients-table td {
                padding: 8px 6px;
                font-size: 11px;
            }
            .add-patient-btn {
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
        <a href="{{ route('homes') }}" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('staffPatients') }} " class="active"><i class="fas fa-users"></i> Patients</a>
        <a href="{{ route('staffAppointments') }}"><i class="fas fa-calendar-alt"></i>Appointment</a>
         <a href="{{ route('bills') }}"><i class="fas fa-file-invoice-dollar"></i>Bill</a>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Patients Management</h4>
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
            <div class="patients-card">
                <div class="patients-header">
                    <h5>Patients Management</h5>
                    <button class="add-patient-btn">
                        <i class="fas fa-plus"></i> Add Patient
                    </button>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="switchTab('current')">
                            Current Patients
                            <span class="tab-badge">5</span>
                        </button>
                        <button class="tab-btn" onclick="switchTab('former')">
                            Former Patients
                            <span class="tab-badge">5</span>
                        </button>
                    </div>
                </div>
                
                <!-- Current Patients Tab Content -->
                <div class="tab-content active" id="currentTab">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputCurrent" placeholder="Search current patients...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('current')">
                                <span>Filter</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountCurrent" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownCurrent">
                                <div class="filter-option selected" onclick="filterPatients('all', 'current')">All Ages</div>
                                <div class="filter-option" onclick="filterPatients('young', 'current')">18-25 years</div>
                                <div class="filter-option" onclick="filterPatients('adult', 'current')">26-35 years</div>
                                <div class="filter-option" onclick="filterPatients('mature', 'current')">36+ years</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnCurrent" style="display: none;" onclick="clearFilters('current')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="patients-table" id="currentPatientsTable">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Address</th>
                                    <th>Age</th>
                                    <th>Admission Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="patient-id">PT001</td>
                                    <td class="patient-name">Maria Santos</td>
                                    <td class="patient-address">San Rafael Buhi</td>
                                    <td class="patient-age">28</td>
                                    <td class="patient-date">June 15, 2025</td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT001')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn" title="Archive" onclick="archivePatient('PT001')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT001')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT002</td>
                                    <td class="patient-name">Ana Cruz</td>
                                    <td class="patient-address">Poblacion Buhi</td>
                                    <td class="patient-age">32</td>
                                    <td class="patient-date">June 12, 2025</td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT002')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn" title="Archive" onclick="archivePatient('PT002')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT002')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT003</td>
                                    <td class="patient-name">Carmen Reyes</td>
                                    <td class="patient-address">San Vicente Buhi</td>
                                    <td class="patient-age">25</td>
                                    <td class="patient-date">June 10, 2025</td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT003')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn" title="Archive" onclick="archivePatient('PT003')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT003')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT004</td>
                                    <td class="patient-name">Elena Villanueva</td>
                                    <td class="patient-address">Bagacay Buhi</td>
                                    <td class="patient-age">30</td>
                                    <td class="patient-date">June 8, 2025</td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT004')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn" title="Archive" onclick="archivePatient('PT004')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT004')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT005</td>
                                    <td class="patient-name">Rosa Mendoza</td>
                                    <td class="patient-address">San Isidro Buhi</td>
                                    <td class="patient-age">26</td>
                                    <td class="patient-date">June 5, 2025</td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT005')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn archive-btn" title="Archive" onclick="archivePatient('PT005')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT005')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Former Patients Tab Content -->
                <div class="tab-content" id="formerTab">
                    <div class="patients-header">
                        <h5>Former Patients</h5>
                        <div class="d-flex gap-2">
                            <button class="add-patient-btn" style="background: #6c757d;" onclick="exportFormerPatients()">
                                <i class="fas fa-download"></i> Export Records
                            </button>
                        </div>
                    </div>
                    
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputFormer" placeholder="Search former patients...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('former')">
                                <span>Filter</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountFormer" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownFormer">
                                <div class="filter-option selected" onclick="filterPatients('all', 'former')">All Statuses</div>
                                <div class="filter-option" onclick="filterPatients('discharged', 'former')">Discharged</div>
                                <div class="filter-option" onclick="filterPatients('completed', 'former')">Completed Treatment</div>
                                <div class="filter-option" onclick="filterPatients('transferred', 'former')">Transferred</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnFormer" style="display: none;" onclick="clearFilters('former')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="patients-table" id="formerPatientsTable">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Patient Name</th>
                                    <th>Address</th>
                                    <th>Age</th>
                                    <th>Discharge Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="patient-id">PT101</td>
                                    <td class="patient-name">Isabella Garcia</td>
                                    <td class="patient-address">Tabgon Buhi</td>
                                    <td class="patient-age">29</td>
                                    <td class="patient-date">May 30, 2025</td>
                                    <td><span class="patient-status status-discharged">Discharged</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View" onclick="viewPatient('PT101')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT101')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT102</td>
                                    <td class="patient-name">Sofia Rodriguez</td>
                                    <td class="patient-address">San Pedro Buhi</td>
                                    <td class="patient-age">31</td>
                                    <td class="patient-date">May 25, 2025</td>
                                    <td><span class="patient-status status-completed">Completed</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View" onclick="viewPatient('PT102')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT102')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT103</td>
                                    <td class="patient-name">Camila Torres</td>
                                    <td class="patient-address">Salvacion Buhi</td>
                                    <td class="patient-age">27</td>
                                    <td class="patient-date">May 20, 2025</td>
                                    <td><span class="patient-status status-discharged">Discharged</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View" onclick="viewPatient('PT103')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT103')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT104</td>
                                    <td class="patient-name">Victoria Morales</td>
                                    <td class="patient-address">Iraya Buhi</td>
                                    <td class="patient-age">33</td>
                                    <td class="patient-date">May 15, 2025</td>
                                    <td><span class="patient-status status-completed">Completed</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View" onclick="viewPatient('PT104')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT104')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="patient-id">PT105</td>
                                    <td class="patient-name">Valentina Castro</td>
                                    <td class="patient-address">Santa Elena Buhi</td>
                                    <td class="patient-age">24</td>
                                    <td class="patient-date">May 10, 2025</td>
                                    <td><span class="patient-status status-discharged">Discharged</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View" onclick="viewPatient('PT105')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete" onclick="deletePatient('PT105')">
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

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (sidebar.classList.contains('mobile-show')) {
                sidebar.classList.remove('mobile-show');
                overlay.classList.remove('show');
            } else {
                sidebar.classList.add('mobile-show');
                overlay.classList.add('show');
            }
        }

        // Tab switching functionality
        function switchTab(tabName) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab
            event.target.classList.add('active');
            
            // Show corresponding content
            if (tabName === 'current') {
                document.getElementById('currentTab').classList.add('active');
            } else if (tabName === 'former') {
                document.getElementById('formerTab').classList.add('active');
            }
        }

        // Filter dropdown functionality
        function toggleFilter(type) {
            const dropdown = document.getElementById(`filterDropdown${type === 'current' ? 'Current' : 'Former'}`);
            dropdown.classList.toggle('show');
            
            // Close other dropdowns
            document.querySelectorAll('.filter-dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('show');
                }
            });
        }

        // Filter patients functionality
        function filterPatients(filterType, tabType) {
            const dropdown = document.getElementById(`filterDropdown${tabType === 'current' ? 'Current' : 'Former'}`);
            const options = dropdown.querySelectorAll('.filter-option');
            
            // Update selected option
            options.forEach(option => option.classList.remove('selected'));
            event.target.classList.add('selected');
            
            // Update filter button text
            const filterBtn = dropdown.previousElementSibling.querySelector('span');
            filterBtn.textContent = event.target.textContent;
            
            // Close dropdown
            dropdown.classList.remove('show');
            
            // Show/hide clear filters button
            const clearBtn = document.getElementById(`clearFiltersBtn${tabType === 'current' ? 'Current' : 'Former'}`);
            if (filterType !== 'all') {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
            
            // Apply filter logic here
            console.log(`Filtering ${tabType} patients by ${filterType}`);
        }

        // Clear filters functionality
        function clearFilters(tabType) {
            const dropdown = document.getElementById(`filterDropdown${tabType === 'current' ? 'Current' : 'Former'}`);
            const options = dropdown.querySelectorAll('.filter-option');
            const filterBtn = dropdown.previousElementSibling.querySelector('span');
            const clearBtn = document.getElementById(`clearFiltersBtn${tabType === 'current' ? 'Current' : 'Former'}`);
            
            // Reset to "All" option
            options.forEach(option => option.classList.remove('selected'));
            options[0].classList.add('selected');
            filterBtn.textContent = 'Filter';
            clearBtn.style.display = 'none';
            
            // Clear search input
            document.getElementById(`searchInput${tabType === 'current' ? 'Current' : 'Former'}`).value = '';
            
            console.log(`Cleared filters for ${tabType} patients`);
        }

        // Search functionality
        document.getElementById('searchInputCurrent').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('currentPatientsTable');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('searchInputFormer').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('formerPatientsTable');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Patient action functions
        function editPatient(patientId) {
            console.log(`Editing patient: ${patientId}`);
            alert(`Edit patient ${patientId} - This would open an edit form`);
        }

        function archivePatient(patientId) {
            if (confirm(`Are you sure you want to archive patient ${patientId}?`)) {
                console.log(`Archiving patient: ${patientId}`);
                alert(`Patient ${patientId} has been archived`);
            }
        }

        function deletePatient(patientId) {
            if (confirm(`Are you sure you want to delete patient ${patientId}? This action cannot be undone.`)) {
                console.log(`Deleting patient: ${patientId}`);
                alert(`Patient ${patientId} has been deleted`);
            }
        }

        function viewPatient(patientId) {
            console.log(`Viewing patient: ${patientId}`);
            alert(`View patient ${patientId} - This would open patient details`);
        }

        function exportFormerPatients() {
            console.log('Exporting former patients records');
            alert('Exporting former patients records - This would generate a downloadable report');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.sidebar') && !event.target.closest('.mobile-menu-btn')) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.remove('mobile-show');
                overlay.classList.remove('show');
            }
        });
    </script>
</body>
</html>