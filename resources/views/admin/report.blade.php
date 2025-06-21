<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Letty's Birthing Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
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
            background: transparent; /* Changed from white to transparent */
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
            top: -3px;
            right: -3px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
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
        
        /* Financial Overview Cards */
        .financial-overview {
            margin-bottom: 30px;
        }
        .overview-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .overview-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .overview-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        .overview-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #4CAF50;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }
        .growth-indicator {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .card-amount {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        .card-subtitle {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        /* Geographical Distribution Section */
        .geography-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .geography-header {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .geography-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        .geography-content {
            display: flex;
            height: 400px;
        }
        .map-container {
            flex: 1;
            position: relative;
        }
        #map {
            width: 100%;
            height: 100%;
        }
        .location-list {
            width: 300px;
            border-left: 1px solid #e9ecef;
            background: #f8f9fa;
            overflow-y: auto;
        }
        .location-list-header {
            padding: 15px 20px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        .location-item {
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            margin: 1px 0;
            transition: all 0.2s ease;
        }
        .location-item:hover {
            background: #f0f8f0;
        }
        .location-name {
            font-size: 13px;
            color: #2c3e50;
            font-weight: 500;
        }
        .location-stats {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .patient-count {
            background: #4CAF50;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .percentage {
            font-size: 12px;
            color: #6c757d;
        }
        .geography-filter {
            padding: 15px 20px;
            background: white;
            border-bottom: 1px solid #e9ecef;
        }
        .filter-btn-geo {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            color: #495057;
            cursor: pointer;
            margin-right: 8px;
            transition: all 0.2s ease;
        }
        .filter-btn-geo:hover, .filter-btn-geo.active {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        
        /* Reports Table */
        .reports-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .reports-header {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .reports-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        .add-report-btn {
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
        .add-report-btn:hover {
            background: linear-gradient(135deg, rgb(47, 184, 52), rgb(37, 167, 41));
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
        
        .table-container {
            overflow-x: auto;
        }
        .reports-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }
        .reports-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px;
            padding: 15px 12px;
            text-align: left;
            white-space: nowrap;
            border-bottom: 2px solid #e9ecef;
        }
        .reports-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #495057;
            font-size: 13px;
            vertical-align: middle;
        }
        .reports-table tbody tr {
            transition: all 0.2s ease;
        }
        .reports-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Status badges */
        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-align: center;
            min-width: 70px;
            display: inline-block;
        }
        .status-paid {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }
        .status-partial {
            background: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }
        .status-unpaid {
            background: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }
        
        /* Service badges */
        .service-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            color: white;
            text-align: center;
            min-width: 80px;
            display: inline-block;
        }
        .service-full {
            background: #4CAF50;
        }
        .service-partial {
            background: #2196F3;
        }
        .service-consult {
            background: #FF9800;
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
            background: #e3f2fd;
            color: #1976d2;
        }
        .action-btn:hover {
            background: #1976d2;
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
            .geography-content {
                flex-direction: column;
                height: auto;
            }
            .map-container {
                height: 300px;
            }
            .location-list {
                width: 100%;
                border-left: none;
                border-top: 1px solid #e9ecef;
                max-height: 250px;
            }
            .header {
                left: 0;
                width: 100%;
            }
        }
        
        @media (max-width: 992px) {
            .reports-header {
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
            .overview-cards {
                grid-template-columns: 1fr;
            }
            .reports-header {
                padding: 20px;
            }
            .search-filter-section {
                padding: 20px;
                gap: 10px;
            }
            .reports-table th,
            .reports-table td {
                padding: 10px 8px;
                font-size: 12px;
            }
            .geography-content {
                height: auto;
            }
            .map-container {
                height: 250px;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 10px;
            }
            .reports-header {
                padding: 15px;
            }
            .search-filter-section {
                padding: 15px;
            }
            .reports-table th,
            .reports-table td {
                padding: 8px 6px;
                font-size: 11px;
            }
            .add-report-btn {
                padding: 8px 16px;
                font-size: 13px;
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
        <a href="{{ route('dashboards') }}" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('patients') }}"><i class="fas fa-users"></i> Patients</a>
        <a href="{{ route('appointments') }}" ><i class="fas fa-calendar"></i> Appointments</a>
        <a href="{{ route('staffs') }}"><i class="fas fa-user-nurse"></i> Staff</a>
        <a href="{{ route('medications') }}"><i class="fas fa-pills"></i> Medication</a>
        <a href="{{ route('reports') }}" class="active"><i class="fas fa-file-alt"></i> Reports</a>
        <a href="{{ route('settings') }}"><i class="fas fa-cog"></i> Settings</a>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Reports</h4>
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
            <!-- Financial Overview -->
            <div class="financial-overview">
                <div class="overview-cards">
                    <div class="overview-card">
                        <div class="card-header">
                            <h6 class="card-title">Revenue Overview</h6>
                            <span class="growth-indicator">+12%</span>
                        </div>
                        <h2 class="card-amount">₱145,230</h2>
                        <p class="card-subtitle">Total for this Month</p>
                    </div>
                    <div class="overview-card">
                        <div class="card-header">
                            <h6 class="card-title">Outstanding Payments</h6>
                            <span class="growth-indicator">+25%</span>
                        </div>
                        <h2 class="card-amount">₱38,450</h2>
                        <p class="card-subtitle">From 12 Patients</p>
                    </div>
                </div>
            </div>

            <div class="reports-section">
                <div class="reports-header">
                    <h5 class="reports-title">Financial Reports</h5>
                    <button class="add-report-btn">
                        <i class="fas fa-plus"></i> Generate Report
                    </button>
                </div>
                
                <div class="search-filter-section">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search reports...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    
                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleFilter('statusFilter')">
                            <span>Status</span>
                            <div class="d-flex align-items-center">
                                <span id="statusFilterCount" class="filter-count" style="display: none;">0</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </button>
                        <div class="filter-dropdown-menu" id="statusFilter">
                            <div class="filter-option selected" onclick="filterReports('status', 'all')">All Status</div>
                            <div class="filter-option" onclick="filterReports('status', 'paid')">Paid</div>
                            <div class="filter-option" onclick="filterReports('status', 'partial')">Partial</div>
                            <div class="filter-option" onclick="filterReports('status', 'unpaid')">Unpaid</div>
                        </div>
                    </div>
                    
                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleFilter('serviceFilter')">
                            <span>Service</span>
                            <div class="d-flex align-items-center">
                                <span id="serviceFilterCount" class="filter-count" style="display: none;">0</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </button>
                        <div class="filter-dropdown-menu" id="serviceFilter">
                            <div class="filter-option selected" onclick="filterReports('service', 'all')">All Services</div>
                            <div class="filter-option" onclick="filterReports('service', 'full')">Full Service</div>
                            <div class="filter-option" onclick="filterReports('service', 'partial')">Partial Service</div>
                            <div class="filter-option" onclick="filterReports('service', 'consult')">Consult Only</div>
                        </div>
                    </div>
                    
                    <button class="clear-filters-btn" id="clearFiltersBtn" style="display: none;" onclick="clearAllFilters()">
                        Clear All Filters
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="reports-table" id="reportsTable">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Patient</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>INV-2025-053</td>
                                <td>Andeng Rancapalo</td>
                                <td><span class="service-badge service-full">Full Service</span></td>
                                <td>May 8, 2025</td>
                                <td>₱3,500.00</td>
                                <td><span class="status-badge status-paid">Paid</span></td>
                                <td>
                                    <button class="action-btn" title="View Details" onclick="viewReportDetails('INV-2025-053')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2025-054</td>
                                <td>Maria Santos</td>
                                <td><span class="service-badge service-partial">Partial Service</span></td>
                                <td>May 3, 2025</td>
                                <td>₱4,500.00</td>
                                <td><span class="status-badge status-paid">Paid</span></td>
                                <td>
                                    <button class="action-btn" title="View Details" onclick="viewReportDetails('INV-2025-054')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2025-055</td>
                                <td>Carmen Reyes</td>
                                <td><span class="service-badge service-consult">Consult Only</span></td>
                                <td>May 7, 2025</td>
                                <td>₱6,500.00</td>
                                <td><span class="status-badge status-partial">Partial</span></td>
                                <td>
                                    <button class="action-btn" title="View Details" onclick="viewReportDetails('INV-2025-055')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2025-056</td>
                                <td>Elena Villanueva</td>
                                <td><span class="service-badge service-partial">Partial Service</span></td>
                                <td>May 5, 2025</td>
                                <td>₱2,500.00</td>
                                <td><span class="status-badge status-unpaid">Unpaid</span></td>
                                <td>
                                    <button class="action-btn" title="View Details" onclick="viewReportDetails('INV-2025-056')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2025-057</td>
                                <td>Rosa Mendoza</td>
                                <td><span class="service-badge service-full">Full Service</span></td>
                                <td>May 16, 2025</td>
                                <td>₱2,500.00</td>
                                <td><span class="status-badge status-paid">Paid</span></td>
                                <td>
                                    <button class="action-btn" title="View Details" onclick="viewReportDetails('INV-2025-057')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2025-058</td>
                                <td>Ana Cruz</td>
                                <td><span class="service-badge service-consult">Consult Only</span></td>
                                <td>May 16, 2025</td>
                                <td>₱1,500.00</td>
                                <td><span class="status-badge status-partial">Partial</span></td>
                                <td>
                                    <button class="action-btn" title="View Details" onclick="viewReportDetails('INV-2025-058')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Patient Geographical Distribution -->
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
                        <div id="map" style="height: 400px; width: 100%; background: #f0f0f0; border-radius: 8px; position: relative;">
                            <!-- Fallback content if Leaflet doesn't load -->
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #666;">
                                <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>Map is loading...</p>
                            </div>
                        </div>
                    </div>
                    <div class="location-list">
                        <div class="location-list-header">
                            Location Distribution
                        </div>
                        <div class="location-item">
                            <div class="location-info">
                                <div class="location-name">Barangay San Rafael</div>
                                <div class="location-address">Buhi, Camarines Sur</div>
                            </div>
                            <div class="location-stats">
                                <span class="patient-count">24</span>
                                <span class="percentage">34.8%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 34.8%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="location-item">
                            <div class="location-info">
                                <div class="location-name">Tambo, Buhi</div>
                                <div class="location-address">Buhi, Camarines Sur</div>
                            </div>
                            <div class="location-stats">
                                <span class="patient-count">18</span>
                                <span class="percentage">26.1%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 26.1%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="location-item">
                            <div class="location-info">
                                <div class="location-name">Lapinisan Falls</div>
                                <div class="location-address">Buhi, Camarines Sur</div>
                            </div>
                            <div class="location-stats">
                                <span class="patient-count">12</span>
                                <span class="percentage">17.4%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 17.4%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="location-item">
                            <div class="location-info">
                                <div class="location-name">Buhi Center</div>
                                <div class="location-address">Buhi, Camarines Sur</div>
                            </div>
                            <div class="location-stats">
                                <span class="patient-count">8</span>
                                <span class="percentage">11.6%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 11.6%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="location-item">
                            <div class="location-info">
                                <div class="location-name">Lolongon Spring Resort</div>
                                <div class="location-address">Buhi, Camarines Sur</div>
                            </div>
                            <div class="location-stats">
                                <span class="patient-count">7</span>
                                <span class="percentage">10.1%</span>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 10.1%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Track active filters
        let activeFilters = {
            status: 'all',
            service: 'all'
        };

        // Initialize map
        let map;
        function initializeMap() {
            try {
                // Initialize map centered on Buhi, Camarines Sur
                map = L.map('map').setView([13.4322, 123.5175], 12);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: 'Buhi, Camarines Sur'
                }).addTo(map);

                // Patient locations with coordinates
                const patientLocations = [
                    { name: 'Barangay San Rafael', lat: 13.4400, lng: 123.5200, patients: 24 },
                    { name: 'Tambo, Buhi', lat: 13.4300, lng: 123.5100, patients: 18 },
                    { name: 'Lapinisan Falls', lat: 13.4250, lng: 123.5250, patients: 12 },
                    { name: 'Buhi Center', lat: 13.4322, lng: 123.5175, patients: 8 },
                    { name: 'Lolongon Spring Resort', lat: 13.4380, lng: 123.5080, patients: 7 }
                ];

                // Add markers for each location
                patientLocations.forEach(location => {
                    const markerSize = Math.max(10, location.patients * 2);
                    
                    // Create custom marker
                    const marker = L.circleMarker([location.lat, location.lng], {
                        radius: markerSize,
                        fillColor: '#667eea',
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map);

                    // Add popup
                    marker.bindPopup(`
                        <div style="text-align: center;">
                            <strong>${location.name}</strong><br>
                            <span style="color: #667eea; font-weight: bold;">${location.patients} patients</span>
                        </div>
                    `);
                });

            } catch (error) {
                console.log('Map initialization failed:', error);
                // Show fallback content
                document.getElementById('map').innerHTML = `
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #666;">
                        <i class="fas fa-map-marked-alt" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>Map unavailable</p>
                        <small>Geographic data visualization</small>
                    </div>
                `;
            }
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }

        // Toggle filter dropdown
        function toggleFilter(filterId) {
            const dropdown = document.getElementById(filterId);
            const allDropdowns = document.querySelectorAll('.filter-dropdown-menu');
            
            // Close other dropdowns
            allDropdowns.forEach(dd => {
                if (dd.id !== filterId) {
                    dd.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('show');
        }

        // Filter reports based on criteria
        function filterReports(filterType, filterValue) {
            const filterOptions = document.querySelectorAll(`#${filterType}Filter .filter-option`);
            
            // Update active filter
            activeFilters[filterType] = filterValue;
            
            // Update selected option
            filterOptions.forEach(option => option.classList.remove('selected'));
            event.target.classList.add('selected');
            
            // Apply all active filters
            applyAllFilters();
            
            // Close dropdown
            document.getElementById(filterType + 'Filter').classList.remove('show');
            
            // Update UI
            updateFilterUI();
        }

        // Apply all active filters
        function applyAllFilters() {
            const rows = document.querySelectorAll('#reportsTable tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                let showRow = true;

                // Status filter
                if (activeFilters.status !== 'all') {
                    const statusBadge = row.querySelector('.status-badge');
                    const statusClass = Array.from(statusBadge.classList).find(cls => cls.startsWith('status-'));
                    const status = statusClass ? statusClass.replace('status-', '') : '';
                    
                    if (status !== activeFilters.status) {
                        showRow = false;
                    }
                }

                // Service filter
                if (activeFilters.service !== 'all' && showRow) {
                    const serviceBadge = row.querySelector('.service-badge');
                    const serviceClass = Array.from(serviceBadge.classList).find(cls => cls.startsWith('service-'));
                    const service = serviceClass ? serviceClass.replace('service-', '') : '';
                    
                    if (service !== activeFilters.service) {
                        showRow = false;
                    }
                }

                // Search filter
                const searchValue = document.getElementById('searchInput').value.toLowerCase();
                if (searchValue && showRow) {
                    const rowText = row.textContent.toLowerCase();
                    if (!rowText.includes(searchValue)) {
                        showRow = false;
                    }
                }

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleCount++;
            });

            // Show "no results" message if needed
            showNoResultsMessage(visibleCount === 0);
        }

        // Update filter UI
        function updateFilterUI() {
            const statusCount = activeFilters.status === 'all' ? 0 : 1;
            const serviceCount = activeFilters.service === 'all' ? 0 : 1;
            const totalFilters = statusCount + serviceCount;

            // Update filter counts
            const statusCountEl = document.getElementById('statusFilterCount');
            const serviceCountEl = document.getElementById('serviceFilterCount');
            
            statusCountEl.style.display = statusCount > 0 ? 'flex' : 'none';
            statusCountEl.textContent = statusCount;
            
            serviceCountEl.style.display = serviceCount > 0 ? 'flex' : 'none';
            serviceCountEl.textContent = serviceCount;

            // Show/hide clear filters button
            const clearBtn = document.getElementById('clearFiltersBtn');
            clearBtn.style.display = totalFilters > 0 ? 'block' : 'none';
        }

        // Clear all filters
        function clearAllFilters() {
            activeFilters = { status: 'all', service: 'all' };
            
            // Reset UI
            document.querySelectorAll('.filter-option').forEach(option => {
                option.classList.remove('selected');
                if (option.textContent.includes('All')) {
                    option.classList.add('selected');
                }
            });
            
            document.getElementById('searchInput').value = '';
            
            applyAllFilters();
            updateFilterUI();
        }

        // Show/hide no results message
        function showNoResultsMessage(show) {
            let noResultsRow = document.querySelector('.no-results-row');
            
            if (show && !noResultsRow) {
                const tbody = document.querySelector('#reportsTable tbody');
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = '<td colspan="7" class="no-results">No reports found matching your criteria.</td>';
                tbody.appendChild(noResultsRow);
            } else if (!show && noResultsRow) {
                noResultsRow.remove();
            }
        }

        // View report details
        function viewReportDetails(invoiceId) {
            alert(`Viewing details for ${invoiceId}\n\nThis would typically open a detailed report view or modal.`);
        }

        // Geography filter functions
        function filterByTimeframe(timeframe) {
            // Remove active class from all buttons
            document.querySelectorAll('.filter-btn-geo').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Here you would typically filter the data based on timeframe
            console.log('Filtering by timeframe:', timeframe);
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            applyAllFilters();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown-menu').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        // Prevent dropdown from closing when clicking inside
        document.querySelectorAll('.filter-dropdown-menu').forEach(dropdown => {
            dropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            updateFilterUI();
            // Initialize map after a short delay to ensure DOM is ready
            setTimeout(initializeMap, 100);
        });
    </script>
</body>
</html>