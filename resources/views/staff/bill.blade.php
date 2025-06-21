<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills - Letty's Birthing Home</title>
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
        .bills-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .bills-header {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .bills-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .add-bill-btn {
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
        .add-bill-btn:hover {
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

        .table-container {
            overflow-x: auto;
        }
        .bills-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }
        .bills-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px;
            padding: 15px 12px;
            border-bottom: 2px solid #e9ecef;
            text-align: left;
            white-space: nowrap;
        }
        .bills-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #495057;
            font-size: 13px;
            vertical-align: middle;
        }
        .bills-table tbody tr {
            transition: all 0.2s ease;
        }
        .bills-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .invoice-id {
            font-weight: 600;
            color: #2c3e50;
        }
        .patient-name {
            font-weight: 500;
            color: #2c3e50;
        }
        .date-info {
            color: #495057;
            font-weight: 500;
        }
        .amount {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
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
        .status-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-partial {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status-unpaid {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        .view-btn {
            background: #e3f2fd;
            color: #1976d2;
        }
        .view-btn:hover {
            background: #1976d2;
            color: white;
        }
        .print-btn {
            background: #f3e5f5;
            color: #7b1fa2;
        }
        .print-btn:hover {
            background: #7b1fa2;
            color: white;
        }
        .edit-btn {
            background: #fff3e0;
            color: #f57c00;
        }
        .edit-btn:hover {
            background: #f57c00;
            color: white;
        }
          .reminder-btn {
            background: #fff3e0;
            color:rgb(53, 53, 59);
        }
        .reminder-btn:hover {
            background:rgb(29, 28, 28);
            color: white;
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
            .bills-header {
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
            .bills-header {
                padding: 20px;
            }
            .search-filter-section {
                padding: 20px;
            }
            .bills-table th,
            .bills-table td {
                padding: 10px 8px;
                font-size: 12px;
            }
            .status-badge {
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
            .bills-header {
                padding: 15px;
            }
            .search-filter-section {
                padding: 15px;
            }
            .bills-table th,
            .bills-table td {
                padding: 8px 6px;
                font-size: 11px;
            }
            .add-bill-btn {
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
                <img src="img/imglogo.png" alt="Logo">
            </div>
            <h5>Letty's Birthing Home</h5>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('homes') }}" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('staffPatients') }} " ><i class="fas fa-users"></i> Patients</a>
        <a href="{{ route('staffAppointments') }} "><i class="fas fa-calendar-alt"></i>Appointment</a>      
        <a href="{{ route('bills') }}" class="active"><i class="fas fa-file-invoice-dollar"></i>Bill</a>
        </div>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Bills Management</h4>
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
            <div class="bills-card">
                <div class="bills-header">
                    <h5>Bills Management</h5>
                    <button class="add-bill-btn">
                        <i class="fas fa-plus"></i> Create New Bill
                    </button>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="switchTab('all')">
                            All Bills
                            <span class="tab-badge">5</span>
                        </button>
                        <button class="tab-btn" onclick="switchTab('unpaid')">
                            Unpaid Bills
                            <span class="tab-badge">3</span>
                        </button>
                    </div>
                </div>

                <!-- All Bills Tab -->
                <div id="allTab" class="tab-content active">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputAll" placeholder="Search bills...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('all')">
                                <span>Filter by Status</span>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownAll">
                                <div class="filter-option selected" onclick="filterBills('all', 'all')">All Status</div>
                                <div class="filter-option" onclick="filterBills('all', 'paid')">Paid</div>
                                <div class="filter-option" onclick="filterBills('all', 'partial')">Partial</div>
                                <div class="filter-option" onclick="filterBills('all', 'unpaid')">Unpaid</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnAll" style="display: none;" onclick="clearFilters('all')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="bills-table" id="billsTableAll">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Patient Name</th>
                                    <th>Date Issued</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-status="paid">
                                    <td class="invoice-id">INV-2025-0103</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 9, 2025</td>
                                    <td class="date-info">May 16, 2025</td>
                                    <td class="amount">₱1500.00</td>
                                    <td><span class="status-badge status-paid">Paid</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="partial">
                                    <td class="invoice-id">INV-2025-0104</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 11, 2025</td>
                                    <td class="date-info">May 18, 2025</td>
                                    <td class="amount">₱3000.00</td>
                                    <td><span class="status-badge status-partial">Partial</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="unpaid">
                                    <td class="invoice-id">INV-2025-0105</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 23, 2025</td>
                                    <td class="date-info">May 30, 2025</td>
                                    <td class="amount">₱2500.00</td>
                                    <td><span class="status-badge status-unpaid">Unpaid</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="unpaid">
                                    <td class="invoice-id">INV-2025-0106</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 21, 2025</td>
                                    <td class="date-info">May 28, 2025</td>
                                    <td class="amount">₱1500.00</td>
                                    <td><span class="status-badge status-unpaid">Unpaid</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="partial">
                                    <td class="invoice-id">INV-2025-0107</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 15, 2025</td>
                                    <td class="date-info">May 22, 2025</td>
                                    <td class="amount">₱4500.00</td>
                                    <td><span class="status-badge status-partial">Partial</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Unpaid Bills Tab -->
                <div id="unpaidTab" class="tab-content">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputUnpaid" placeholder="Search unpaid bills...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('unpaid')">
                                <span>Filter by Amount</span>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownUnpaid">
                                <div class="filter-option selected" onclick="filterBills('unpaid', 'all')">All Amounts</div>
                                <div class="filter-option" onclick="filterBills('unpaid', 'low')">Below ₱2000</div>
                                <div class="filter-option" onclick="filterBills('unpaid', 'medium')">₱2000 - ₱4000</div>
                                <div class="filter-option" onclick="filterBills('unpaid', 'high')">Above ₱4000</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnUnpaid" style="display: none;" onclick="clearFilters('unpaid')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="bills-table" id="billsTableUnpaid">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Patient Name</th>
                                    <th>Date Issued</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Days Overdue</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-status="unpaid" data-amount="2500">
                                    <td class="invoice-id">INV-2025-0105</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 23, 2025</td>
                                    <td class="date-info">May 30, 2025</td>
                                    <td class="amount">₱2500.00</td>
                                    <td class="date-info" style="color: #dc3545; font-weight: 600;">21 days</td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                         <button class="action-btn reminder-btn" title="Send Reminder">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="unpaid" data-amount="1500">
                                    <td class="invoice-id">INV-2025-0106</td>
                                    <td class="patient-name">Andeng Rancopoillo</td>
                                    <td class="date-info">May 21, 2025</td>
                                    <td class="date-info">May 28, 2025</td>
                                    <td class="amount">₱1500.00</td>
                                    <td class="date-info" style="color: #dc3545; font-weight: 600;">23 days</td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-status="unpaid" data-amount="3200">
                                    <td class="invoice-id">INV-2025-0108</td>
                                    <td class="patient-name">Maria Santos</td>
                                    <td class="date-info">June 1, 2025</td>
                                    <td class="date-info">June 8, 2025</td>
                                    <td class="amount">₱3200.00</td>
                                    <td class="date-info" style="color: #dc3545; font-weight: 600;">12 days</td>
                                    <td class="actions-cell">
                                        <button class="action-btn view-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn print-btn" title="Print">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
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
        // Global variables
        let currentFilter = {
            all: 'all',
            unpaid: 'all'
        };

        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }

        // Tab switching functionality
        function switchTab(tab) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            event.target.classList.add('active');
            document.getElementById(tab + 'Tab').classList.add('active');
        }

        // Toggle filter dropdown
        function toggleFilter(tab) {
            const dropdown = document.getElementById('filterDropdown' + capitalize(tab));
            dropdown.classList.toggle('show');
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.filter-dropdown')) {
                    dropdown.classList.remove('show');
                }
            });
        }

        // Filter bills functionality
        function filterBills(tab, filterType) {
            const table = document.getElementById('billsTable' + capitalize(tab));
            const rows = table.querySelectorAll('tbody tr');
            const clearBtn = document.getElementById('clearFiltersBtn' + capitalize(tab));
            
            // Update current filter
            currentFilter[tab] = filterType;
            
            // Update selected option in dropdown
            const dropdown = document.getElementById('filterDropdown' + capitalize(tab));
            dropdown.querySelectorAll('.filter-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.classList.add('selected');
            
            // Update filter button text
            const filterBtn = dropdown.previousElementSibling;
            const filterText = event.target.textContent;
            filterBtn.querySelector('span').textContent = filterText;
            
            // Show/hide clear filters button
            if (filterType !== 'all') {
                clearBtn.style.display = 'block';
            } else {
                clearBtn.style.display = 'none';
            }
            
            // Filter rows based on criteria
            rows.forEach(row => {
                let show = true;
                
                if (tab === 'all') {
                    if (filterType !== 'all') {
                        const status = row.getAttribute('data-status');
                        show = status === filterType;
                    }
                } else if (tab === 'unpaid') {
                    if (filterType !== 'all') {
                        const amount = parseFloat(row.getAttribute('data-amount'));
                        switch(filterType) {
                            case 'low':
                                show = amount < 2000;
                                break;
                            case 'medium':
                                show = amount >= 2000 && amount <= 4000;
                                break;
                            case 'high':
                                show = amount > 4000;
                                break;
                        }
                    }
                }
                
                row.style.display = show ? '' : 'none';
            });
            
            // Close dropdown
            dropdown.classList.remove('show');
        }

        // Clear all filters
        function clearFilters(tab) {
            const table = document.getElementById('billsTable' + capitalize(tab));
            const rows = table.querySelectorAll('tbody tr');
            const clearBtn = document.getElementById('clearFiltersBtn' + capitalize(tab));
            const dropdown = document.getElementById('filterDropdown' + capitalize(tab));
            const filterBtn = dropdown.previousElementSibling;
            
            // Reset filter
            currentFilter[tab] = 'all';
            
            // Show all rows
            rows.forEach(row => {
                row.style.display = '';
            });
            
            // Hide clear button
            clearBtn.style.display = 'none';
            
            // Reset dropdown selection
            dropdown.querySelectorAll('.filter-option').forEach(option => {
                option.classList.remove('selected');
            });
            dropdown.querySelector('.filter-option').classList.add('selected');
            
            // Reset filter button text
            if (tab === 'all') {
                filterBtn.querySelector('span').textContent = 'Filter by Status';
            } else {
                filterBtn.querySelector('span').textContent = 'Filter by Amount';
            }
            
            // Clear search
            const searchInput = document.getElementById('searchInput' + capitalize(tab));
            searchInput.value = '';
        }

        // Search functionality
        function setupSearch() {
            const searchInputAll = document.getElementById('searchInputAll');
            const searchInputUnpaid = document.getElementById('searchInputUnpaid');
            
            searchInputAll.addEventListener('input', function() {
                searchBills('all', this.value);
            });
            
            searchInputUnpaid.addEventListener('input', function() {
                searchBills('unpaid', this.value);
            });
        }

        function searchBills(tab, searchTerm) {
            const table = document.getElementById('billsTable' + capitalize(tab));
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const invoiceId = row.querySelector('.invoice-id').textContent.toLowerCase();
                const patientName = row.querySelector('.patient-name').textContent.toLowerCase();
                const searchLower = searchTerm.toLowerCase();
                
                const matchesSearch = invoiceId.includes(searchLower) || patientName.includes(searchLower);
                const matchesFilter = currentFilter[tab] === 'all' || checkFilterMatch(row, tab, currentFilter[tab]);
                
                row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
            });
        }

        function checkFilterMatch(row, tab, filterType) {
            if (tab === 'all') {
                const status = row.getAttribute('data-status');
                return status === filterType;
            } else if (tab === 'unpaid') {
                const amount = parseFloat(row.getAttribute('data-amount'));
                switch(filterType) {
                    case 'low':
                        return amount < 2000;
                    case 'medium':
                        return amount >= 2000 && amount <= 4000;
                    case 'high':
                        return amount > 4000;
                    default:
                        return true;
                }
            }
            return true;
        }

        // Utility function to capitalize first letter
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Initialize search functionality when page loads
        document.addEventListener('DOMContentLoaded', function() {
            setupSearch();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown-menu').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    </script>
</body>
</html>