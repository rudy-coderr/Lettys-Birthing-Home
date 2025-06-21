<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Management - Letty's Birthing Home</title>
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
        .medication-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .medication-header {
            padding: 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .medication-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }
        .add-medication-btn {
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
        .add-medication-btn:hover {
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
        .medication-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }
        .medication-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            font-size: 13px;
            padding: 15px 12px;
            border-bottom: 2px solid #e9ecef;
            text-align: left;
            white-space: nowrap;
        }
        .medication-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #495057;
            font-size: 13px;
            vertical-align: middle;
        }
        .medication-table tbody tr {
            transition: all 0.2s ease;
        }
        .medication-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Image Styles */
        .item-image {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .item-image:hover {
            border-color: #4CAF50;
            transform: scale(1.05);
        }
        .image-placeholder {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .image-placeholder:hover {
            border-color: #4CAF50;
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.05);
        }

        /* Image Modal */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            animation: fadeIn 0.3s ease;
        }
        .image-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .image-modal img {
            width: 100%;
            height: auto;
            display: block;
        }
        .image-modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .image-modal-close:hover {
            background: rgba(0,0,0,0.8);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .item-code {
            font-weight: 600;
            color: #2c3e50;
        }
        .item-name {
            font-weight: 500;
            color: #2c3e50;
        }
        .quantity-info {
            color: #495057;
            font-weight: 500;
        }
        .type-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }
        .type-tablet {
            background: #4CAF50;
            color: white;
        }
        .type-syrup {
            background: #2196F3;
            color: white;
        }
        .type-injection {
            background: #FF9800;
            color: white;
        }
        .type-capsule {
            background: #9C27B0;
            color: white;
        }
        .type-disposable {
            background: #607D8B;
            color: white;
        }
        .type-equipment {
            background: #795548;
            color: white;
        }
        .type-consumable {
            background: #009688;
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
        .status-available {
            background: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
        }
        .status-low {
            background: #FFF3CD;
            color: #856404;
            border: 1px solid #FFEAA7;
        }
        .status-out {
            background: #F8D7DA;
            color: #721C24;
            border: 1px solid #F5C6CB;
        }
        .status-expired {
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
        .restock-btn {
            background: #e8f5e9;
            color: rgb(100, 192, 192);
        }
        .restock-btn:hover {
            background: rgb(48, 100, 100);
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
            .medication-header {
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
            .medication-header {
                padding: 20px;
            }
            .search-filter-section {
                padding: 20px;
            }
            .medication-table th,
            .medication-table td {
                padding: 10px 8px;
                font-size: 12px;
            }
            .type-badge, .status-badge {
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
            .item-image, .image-placeholder {
                width: 35px;
                height: 35px;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 10px;
            }
            .medication-header {
                padding: 15px;
            }
            .search-filter-section {
                padding: 15px;
            }
            .medication-table th,
            .medication-table td {
                padding: 8px 6px;
                font-size: 11px;
            }
            .add-medication-btn {
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
            .item-image, .image-placeholder {
                width: 30px;
                height: 30px;
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
        <a href="{{ route('appointments') }}" ><i class="fas fa-calendar"></i> Appointments</a>
        <a href="{{ route('staffs') }}"><i class="fas fa-user-nurse"></i> Staff</a>
        <a href="{{ route('medications') }}"  class="active"><i class="fas fa-pills"></i> Medication</a>
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
                <h4>Medication Management</h4>
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
            <div class="medication-card">
                <div class="medication-header">
                    <h5>Medication Inventory</h5>
                    <button class="add-medication-btn">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
                
                <!-- Tab Navigation -->
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn active" onclick="switchTab('medicines')">
                            <i class="fas fa-pills"></i> Medicines
                        </button>
                        <button class="tab-btn" onclick="switchTab('supplies')">
                            <i class="fas fa-boxes"></i> Medical Supplies
                        </button>
                    </div>
                </div>

                <!-- Medicines Tab -->
                <div id="medicinesTab" class="tab-content active">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputMedicines" placeholder="Search medicines..." oninput="searchItems('medicines')">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('medicines')">
                                <span>Type</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountMedicines" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownMedicines">
                                <div class="filter-option selected" onclick="filterItems('medicines', 'type', 'all')">All Types</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'type', 'tablet')">Tablets</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'type', 'syrup')">Syrups</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'type', 'injection')">Injections</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'type', 'capsule')">Capsules</div>
                            </div>
                        </div>

                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('medicinesStatus')">
                                <span>Status</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountMedicinesStatus" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownMedicinesStatus">
                                <div class="filter-option selected" onclick="filterItems('medicines', 'status', 'all')">All Status</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'status', 'available')">Available</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'status', 'low')">Low Stock</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'status', 'out')">Out of Stock</div>
                                <div class="filter-option" onclick="filterItems('medicines', 'status', 'expired')">Expired</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnMedicines" style="display: none;" onclick="clearFilters('medicines')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="medication-table" id="medicinesTable">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Code</th>
                                    <th>Medicine Name</th>
                                    <th>Type</th>
                                    <th>Dosage</th>
                                    <th>Quantity</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-type="tablet" data-status="available">
                                    <td>
                                        <img src="{{ asset('img/paracetamol.jpg') }}" 
                                             alt="Paracetamol" class="item-image" 
                                             onclick="openImageModal(this.src, 'Paracetamol')">
                                    </td>
                                    <td class="item-code">MED001</td>
                                    <td class="item-name">Paracetamol</td>
                                    <td><span class="type-badge type-tablet">Tablet</span></td>
                                    <td>500mg</td>
                                    <td class="quantity-info">250 pcs</td>
                                    <td>Dec 2025</td>
                                    <td><span class="status-badge status-available">Available</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn restock-btn" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-type="syrup" data-status="low">
                                    <td>
                                         <img src="{{ asset('img/syrup.jpg') }}" 
                                             alt="Amoxicillin" class="item-image" 
                                             onclick="openImageModal(this.src, 'Amoxicillin')">
                                    </td>
                                    <td class="item-code">MED002</td>
                                    <td class="item-name">Amoxicillin Syrup</td>
                                    <td><span class="type-badge type-syrup">Syrup</span></td>
                                    <td>250mg/5ml</td>
                                    <td class="quantity-info">15 bottles</td>
                                    <td>Mar 2025</td>
                                    <td><span class="status-badge status-low">Low Stock</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn restock-btn" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-type="injection" data-status="available">
                                    <td>
                                        <img src="{{ asset('img/insulin.jpg') }}" 
                                             alt="Insulin" class="item-image" 
                                             onclick="openImageModal(this.src, 'Insulin')">
                                    </td>
                                    <td class="item-code">MED003</td>
                                    <td class="item-name">Insulin</td>
                                    <td><span class="type-badge type-injection">Injection</span></td>
                                    <td>100IU/ml</td>
                                    <td class="quantity-info">30 vials</td>
                                    <td>Jan 2026</td>
                                    <td><span class="status-badge status-available">Available</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn restock-btn" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <div id="noResultsMedicines" class="no-results" style="display: none;">
                            <i class="fas fa-search"></i>
                            <p>No medicines found matching your search criteria.</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Supplies Tab -->
                <div id="suppliesTab" class="tab-content">
                    <div class="search-filter-section">
                        <div class="search-box">
                            <input type="text" id="searchInputSupplies" placeholder="Search medical supplies..." oninput="searchItems('supplies')">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        
                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('supplies')">
                                <span>Type</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountSupplies" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownSupplies">
                                <div class="filter-option selected" onclick="filterItems('supplies', 'type', 'all')">All Types</div>
                                <div class="filter-option" onclick="filterItems('supplies', 'type', 'disposable')">Disposables</div>
                                <div class="filter-option" onclick="filterItems('supplies', 'type', 'equipment')">Equipment</div>
                                <div class="filter-option" onclick="filterItems('supplies', 'type', 'consumable')">Consumables</div>
                            </div>
                        </div>

                        <div class="filter-dropdown">
                            <button class="filter-btn" onclick="toggleFilter('suppliesStatus')">
                                <span>Status</span>
                                <div class="d-flex align-items-center">
                                    <span id="filterCountSuppliesStatus" class="filter-count" style="display: none;">0</span>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </button>
                            <div class="filter-dropdown-menu" id="filterDropdownSuppliesStatus">
                                <div class="filter-option selected" onclick="filterItems('supplies', 'status', 'all')">All Status</div>
                                <div class="filter-option" onclick="filterItems('supplies', 'status', 'available')">Available</div>
                                <div class="filter-option" onclick="filterItems('supplies', 'status', 'low')">Low Stock</div>
                                <div class="filter-option" onclick="filterItems('supplies', 'status', 'out')">Out of Stock</div>
                            </div>
                        </div>
                        
                        <button class="clear-filters-btn" id="clearFiltersBtnSupplies" style="display: none;" onclick="clearFilters('supplies')">
                            Clear Filters
                        </button>
                    </div>

                    <div class="table-container">
                        <table class="medication-table" id="suppliesTable">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Code</th>
                                    <th>Item Name</th>
                                    <th>Type</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Last Restocked</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-type="disposable" data-status="available">
                                    <td>
                                        <img src="{{ asset('img/Syringes.jpg') }}" 
                                             alt="Syringes" class="item-image" 
                                             onclick="openImageModal(this.src, 'Syringes')">
                                    </td>
                                    <td class="item-code">SUP001</td>
                                    <td class="item-name">Disposable Syringes</td>
                                    <td><span class="type-badge type-disposable">Disposable</span></td>
                                    <td>5ml</td>
                                    <td class="quantity-info">500 pcs</td>
                                    <td>Dec 2024</td>
                                    <td><span class="status-badge status-available">Available</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn restock-btn" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-type="equipment" data-status="available">
                                    <td>
                                         <img src="{{ asset('img/bloodPressure.jpg') }}" 
                                             alt="Blood Pressure Monitor" class="item-image" 
                                             onclick="openImageModal(this.src, 'Blood Pressure Monitor')">
                                    </td>
                                    <td class="item-code">SUP002</td>
                                    <td class="item-name">Blood Pressure Monitor</td>
                                    <td><span class="type-badge type-equipment">Equipment</span></td>
                                    <td>Unit</td>
                                    <td class="quantity-info">3 units</td>
                                    <td>Nov 2024</td>
                                    <td><span class="status-badge status-available">Available</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn restock-btn" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr data-type="consumable" data-status="low">
                                    <td>
                                        <img src="{{ asset('img/padz.jpg') }}" 
                                             alt="Gauze Pads" class="item-image" 
                                             onclick="openImageModal(this.src, 'Gauze Pads')">
                                    </td>
                                    <td class="item-code">SUP003</td>
                                    <td class="item-name">Sterile Gauze Pads</td>
                                    <td><span class="type-badge type-consumable">Consumable</span></td>
                                    <td>4x4 inch</td>
                                    <td class="quantity-info">25 packs</td>
                                    <td>Oct 2024</td>
                                    <td><span class="status-badge status-low">Low Stock</span></td>
                                    <td class="actions-cell">
                                        <button class="action-btn edit-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn restock-btn" title="Restock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="action-btn delete-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="noResultsSupplies" class="no-results" style="display: none;">
                            <i class="fas fa-search"></i>
                            <p>No medical supplies found matching your search criteria.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <div class="image-modal-content">
            <img id="modalImage" src="" alt="">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       // Global variables to store filter states
let activeFilters = {
    medicines: { type: 'all', status: 'all' },
    supplies: { type: 'all', status: 'all' }
};

// Mobile sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar.classList.contains('mobile-hidden')) {
        sidebar.classList.remove('mobile-hidden');
        sidebar.classList.add('mobile-show');
        overlay.classList.add('show');
    } else {
        sidebar.classList.add('mobile-hidden');
        sidebar.classList.remove('mobile-show');
        overlay.classList.remove('show');
    }
}

// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName + 'Tab').classList.add('active');
    
    // Add active class to clicked tab button
    event.target.classList.add('active');
}

// Search functionality
function searchItems(category) {
    const searchInput = document.getElementById(`searchInput${category.charAt(0).toUpperCase() + category.slice(1)}`);
    const searchTerm = searchInput.value.toLowerCase();
    const table = document.getElementById(`${category}Table`);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const noResults = document.getElementById(`noResults${category.charAt(0).toUpperCase() + category.slice(1)}`);
    
    let visibleRows = 0;
    
    Array.from(rows).forEach(row => {
        const itemName = row.cells[2].textContent.toLowerCase();
        const itemCode = row.cells[1].textContent.toLowerCase();
        const dosageOrUnit = row.cells[4].textContent.toLowerCase();
        
        const matchesSearch = itemName.includes(searchTerm) || 
                            itemCode.includes(searchTerm) || 
                            dosageOrUnit.includes(searchTerm);
        
        const matchesFilters = checkFilterMatch(row, category);
        
        if (matchesSearch && matchesFilters) {
            row.style.display = '';
            visibleRows++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    if (visibleRows === 0) {
        noResults.style.display = 'block';
        table.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        table.style.display = 'table';
    }
}

// Check if row matches current filters
function checkFilterMatch(row, category) {
    const filters = activeFilters[category];
    const rowType = row.getAttribute('data-type');
    const rowStatus = row.getAttribute('data-status');
    
    const typeMatch = filters.type === 'all' || rowType === filters.type;
    const statusMatch = filters.status === 'all' || rowStatus === filters.status;
    
    return typeMatch && statusMatch;
}

// Toggle filter dropdown
function toggleFilter(filterId) {
    const dropdown = document.getElementById(`filterDropdown${filterId.charAt(0).toUpperCase() + filterId.slice(1)}`);
    const button = dropdown.previousElementSibling;
    
    // Close other dropdowns
    document.querySelectorAll('.filter-dropdown-menu').forEach(menu => {
        if (menu !== dropdown) {
            menu.classList.remove('show');
            menu.previousElementSibling.classList.remove('active');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('show');
    button.classList.toggle('active');
}

// Filter items by type or status
function filterItems(category, filterType, value) {
    // Update active filters
    activeFilters[category][filterType] = value;
    
    // Update UI
    const filterId = filterType === 'type' ? category : category + 'Status';
    const dropdown = document.getElementById(`filterDropdown${filterId.charAt(0).toUpperCase() + filterId.slice(1)}`);
    const options = dropdown.querySelectorAll('.filter-option');
    
    // Update selected option
    options.forEach(option => {
        option.classList.remove('selected');
        if (option.textContent.toLowerCase().includes(value) || 
            (value === 'all' && option.textContent.toLowerCase().includes('all'))) {
            option.classList.add('selected');
        }
    });
    
    // Close dropdown
    dropdown.classList.remove('show');
    dropdown.previousElementSibling.classList.remove('active');
    
    // Apply filters
    applyFilters(category);
    updateFilterCounts(category);
}

// Apply all active filters
function applyFilters(category) {
    const table = document.getElementById(`${category}Table`);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const noResults = document.getElementById(`noResults${category.charAt(0).toUpperCase() + category.slice(1)}`);
    const searchInput = document.getElementById(`searchInput${category.charAt(0).toUpperCase() + category.slice(1)}`);
    
    let visibleRows = 0;
    
    Array.from(rows).forEach(row => {
        const matchesFilters = checkFilterMatch(row, category);
        
        // Also check search term if present
        const searchTerm = searchInput.value.toLowerCase();
        let matchesSearch = true;
        
        if (searchTerm) {
            const itemName = row.cells[2].textContent.toLowerCase();
            const itemCode = row.cells[1].textContent.toLowerCase();
            const dosageOrUnit = row.cells[4].textContent.toLowerCase();
            
            matchesSearch = itemName.includes(searchTerm) || 
                          itemCode.includes(searchTerm) || 
                          dosageOrUnit.includes(searchTerm);
        }
        
        if (matchesFilters && matchesSearch) {
            row.style.display = '';
            visibleRows++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    if (visibleRows === 0) {
        noResults.style.display = 'block';
        table.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        table.style.display = 'table';
    }
}

// Update filter count badges
function updateFilterCounts(category) {
    const filters = activeFilters[category];
    let activeFilterCount = 0;
    
    if (filters.type !== 'all') activeFilterCount++;
    if (filters.status !== 'all') activeFilterCount++;
    
    // Update type filter count
    const typeCountElement = document.getElementById(`filterCount${category.charAt(0).toUpperCase() + category.slice(1)}`);
    const statusCountElement = document.getElementById(`filterCount${category.charAt(0).toUpperCase() + category.slice(1)}Status`);
    const clearButton = document.getElementById(`clearFiltersBtn${category.charAt(0).toUpperCase() + category.slice(1)}`);
    
    if (filters.type !== 'all') {
        typeCountElement.textContent = '1';
        typeCountElement.style.display = 'flex';
    } else {
        typeCountElement.style.display = 'none';
    }
    
    if (filters.status !== 'all') {
        statusCountElement.textContent = '1';
        statusCountElement.style.display = 'flex';
    } else {
        statusCountElement.style.display = 'none';
    }
    
    // Show/hide clear filters button
    if (activeFilterCount > 0) {
        clearButton.style.display = 'block';
    } else {
        clearButton.style.display = 'none';
    }
}

// Clear all filters
function clearFilters(category) {
    // Reset filters
    activeFilters[category] = { type: 'all', status: 'all' };
    
    // Reset UI
    const typeDropdown = document.getElementById(`filterDropdown${category.charAt(0).toUpperCase() + category.slice(1)}`);
    const statusDropdown = document.getElementById(`filterDropdown${category.charAt(0).toUpperCase() + category.slice(1)}Status`);
    
    [typeDropdown, statusDropdown].forEach(dropdown => {
        const options = dropdown.querySelectorAll('.filter-option');
        options.forEach((option, index) => {
            option.classList.remove('selected');
            if (index === 0) { // First option is usually "All"
                option.classList.add('selected');
            }
        });
    });
    
    // Apply filters and update counts
    applyFilters(category);
    updateFilterCounts(category);
}

// Image modal functions
function openImageModal(imageSrc, itemName) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageSrc;
    modalImage.alt = itemName;
    modal.style.display = 'block';
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Image upload modal (placeholder function)
function openImageUploadModal(itemCode) {
    alert(`Image upload functionality for item ${itemCode} would be implemented here.\n\nThis would typically open a file picker or camera interface.`);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        closeImageModal();
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const isFilterButton = event.target.closest('.filter-btn');
    const isDropdownMenu = event.target.closest('.filter-dropdown-menu');
    
    if (!isFilterButton && !isDropdownMenu) {
        document.querySelectorAll('.filter-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
            menu.previousElementSibling.classList.remove('active');
        });
    }
});

// Action button handlers
document.addEventListener('DOMContentLoaded', function() {
    // Edit button handlers
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const itemCode = row.querySelector('.item-code').textContent;
            const itemName = row.querySelector('.item-name').textContent;
            alert(`Edit functionality for ${itemName} (${itemCode}) would be implemented here.`);
        });
    });
    
    // Restock button handlers
    document.querySelectorAll('.restock-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const itemCode = row.querySelector('.item-code').textContent;
            const itemName = row.querySelector('.item-name').textContent;
            const newQuantity = prompt(`Enter new quantity for ${itemName} (${itemCode}):`);
            
            if (newQuantity !== null && newQuantity !== '') {
                const quantityCell = row.querySelector('.quantity-info');
                const unit = quantityCell.textContent.split(' ')[1] || 'pcs';
                quantityCell.textContent = `${newQuantity} ${unit}`;
                
                // Update status badge based on quantity
                const statusBadge = row.querySelector('.status-badge');
                const qty = parseInt(newQuantity);
                
                if (qty === 0) {
                    statusBadge.className = 'status-badge status-out';
                    statusBadge.textContent = 'Out of Stock';
                    row.setAttribute('data-status', 'out');
                } else if (qty < 50) {
                    statusBadge.className = 'status-badge status-low';
                    statusBadge.textContent = 'Low Stock';
                    row.setAttribute('data-status', 'low');
                } else {
                    statusBadge.className = 'status-badge status-available';
                    statusBadge.textContent = 'Available';
                    row.setAttribute('data-status', 'available');
                }
                
                alert(`${itemName} quantity updated to ${newQuantity}!`);
            }
        });
    });
    
    // Delete button handlers
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const itemCode = row.querySelector('.item-code').textContent;
            const itemName = row.querySelector('.item-name').textContent;
            
            if (confirm(`Are you sure you want to delete ${itemName} (${itemCode})?`)) {
                row.remove();
                alert(`${itemName} has been deleted from inventory.`);
                
                // Reapply filters after deletion
                const activeTab = document.querySelector('.tab-content.active');
                const category = activeTab.id === 'medicinesTab' ? 'medicines' : 'supplies';
                applyFilters(category);
            }
        });
    });
    
    // Add medication button handler
    document.querySelector('.add-medication-btn').addEventListener('click', function() {
        alert('Add new medication/supply functionality would be implemented here.\n\nThis would typically open a form modal for entering new item details.');
    });
    
    // Initialize responsive sidebar for mobile
    function handleResize() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-hidden', 'mobile-show');
            overlay.classList.remove('show');
        } else {
            sidebar.classList.add('mobile-hidden');
            sidebar.classList.remove('mobile-show');
            overlay.classList.remove('show');
        }
    }
    
    // Initial check and event listener
    handleResize();
    window.addEventListener('resize', handleResize);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // ESC key to close modal
        if (event.key === 'Escape') {
            closeImageModal();
        }
        
        // Ctrl/Cmd + F to focus search
        if ((event.ctrlKey || event.metaKey) && event.key === 'f') {
            event.preventDefault();
            const activeTab = document.querySelector('.tab-content.active');
            const category = activeTab.id === 'medicinesTab' ? 'Medicines' : 'Supplies';
            const searchInput = document.getElementById(`searchInput${category}`);
            searchInput.focus();
        }
    });
});
</script>