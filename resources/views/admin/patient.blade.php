
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

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .search-box input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
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
            <a href="{{ route('patients') }} " class="active"><i class="fas fa-users"></i> Patients</a>
            <a href="{{ route('appointments') }}"><i class="fas fa-calendar"></i> Appointments</a>
            <a href="{{ route('staffs') }} "><i class="fas fa-user-nurse"></i> Staff</a>
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
                    <h5>All Patients</h5>
                    <button class="add-patient-btn">
                        <i class="fas fa-plus"></i> Add Patient
                    </button>
                </div>
                
                <div class="search-filter-section">
                    <div class="search-box">
                        <input type="text" id="searchInputAll" placeholder="Search patients...">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    
                    <div class="filter-dropdown">
                        <button class="filter-btn" onclick="toggleFilter()">
                            <span>Filter</span>
                            <div class="d-flex align-items-center">
                                <span id="filterCount" class="filter-count" style="display: none;">0</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </button>
                        <div class="filter-dropdown-menu" id="filterDropdown">
                            <div class="filter-option selected" onclick="filterPatients('all')">All Ages</div>
                            <div class="filter-option" onclick="filterPatients('young')">18-25 years</div>
                            <div class="filter-option" onclick="filterPatients('adult')">26-35 years</div>
                            <div class="filter-option" onclick="filterPatients('mature')">36+ years</div>
                        </div>
                    </div>
                    
                    <button class="clear-filters-btn" id="clearFiltersBtn" style="display: none;" onclick="clearFilters()">
                        Clear Filters
                    </button>
                </div>

                <div class="table-container">
                    <table class="patients-table" id="patientsTable">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Address</th>
                                <th>Age</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="patient-id">PT001</td>
                                <td class="patient-name">Maria Santos</td>
                                <td class="patient-address">San Rafael Buhi</td>
                                <td class="patient-age">28</td>
                                <td class="actions-cell">
                                    <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT001')">
                                        <i class="fas fa-edit"></i>
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
                                <td class="actions-cell">
                                    <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT002')">
                                        <i class="fas fa-edit"></i>
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
                                <td class="actions-cell">
                                    <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT003')">
                                        <i class="fas fa-edit"></i>
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
                                <td class="actions-cell">
                                    <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT004')">
                                        <i class="fas fa-edit"></i>
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
                                <td class="actions-cell">
                                    <button class="action-btn edit-btn" title="Edit" onclick="editPatient('PT005')">
                                        <i class="fas fa-edit"></i>
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

        // Filter functionality
        function toggleFilter() {
            const dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('show');
        }

        function filterPatients(ageGroup) {
            const rows = document.querySelectorAll('#patientsTable tbody tr:not(#noResultsRow)');
            const filterOptions = document.querySelectorAll('.filter-option');
            const clearBtn = document.getElementById('clearFiltersBtn');
            const filterCount = document.getElementById('filterCount');
            
            // Update selected option
            filterOptions.forEach(option => option.classList.remove('selected'));
            event.target.classList.add('selected');
            
            let filteredCount = 0;
            
            // Filter rows based on age group
            rows.forEach(row => {
                const ageCell = row.querySelector('.patient-age');
                if (!ageCell) return;
                
                const age = parseInt(ageCell.textContent);
                let showRow = false;
                
                switch(ageGroup) {
                    case 'all':
                        showRow = true;
                        break;
                    case 'young': // 18-25 years
                        showRow = age >= 18 && age <= 25;
                        break;
                    case 'adult': // 26-35 years
                        showRow = age >= 26 && age <= 35;
                        break;
                    case 'mature': // 36+ years
                        showRow = age >= 36;
                        break;
                }
                
                if (showRow) {
                    row.style.display = '';
                    filteredCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Update filter count and clear button visibility
            if (ageGroup !== 'all') {
                clearBtn.style.display = 'block';
                filterCount.textContent = filteredCount;
                filterCount.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
                filterCount.style.display = 'none';
            }
            
            // Show no results message if needed
            showNoResults(filteredCount === 0 && ageGroup !== 'all');
            
            // Close dropdown
            document.getElementById('filterDropdown').classList.remove('show');
        }

        function clearFilters() {
            const rows = document.querySelectorAll('#patientsTable tbody tr:not(#noResultsRow)');
            const filterOptions = document.querySelectorAll('.filter-option');
            const clearBtn = document.getElementById('clearFiltersBtn');
            const filterCount = document.getElementById('filterCount');
            
            // Show all rows
            rows.forEach(row => row.style.display = '');
            
            // Reset filter selection
            filterOptions.forEach(option => option.classList.remove('selected'));
            filterOptions[0].classList.add('selected');
            
            // Hide clear button and filter count
            clearBtn.style.display = 'none';
            filterCount.style.display = 'none';
            
            // Hide no results message
            showNoResults(false);
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#patientsTable tbody tr:not(#noResultsRow)');
            let visibleRows = 0;
            
            rows.forEach(row => {
                // Only search in visible rows (after filter)
                if (row.style.display === 'none') return;
                
                const patientId = row.querySelector('.patient-id').textContent.toLowerCase();
                const patientName = row.querySelector('.patient-name').textContent.toLowerCase();
                const patientAddress = row.querySelector('.patient-address').textContent.toLowerCase();
                const patientAge = row.querySelector('.patient-age').textContent.toLowerCase();
                
                if (patientId.includes(searchTerm) || 
                    patientName.includes(searchTerm) || 
                    patientAddress.includes(searchTerm) || 
                    patientAge.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show no results message if no rows are visible
            showNoResults(visibleRows === 0 && searchTerm !== '');
        });

        // Show/hide no results message
        function showNoResults(show) {
            let noResultsRow = document.getElementById('noResultsRow');
            
            if (show && !noResultsRow) {
                const tbody = document.querySelector('#patientsTable tbody');
                noResultsRow = document.createElement('tr');
                noResultsRow.id = 'noResultsRow';
                noResultsRow.innerHTML = '<td colspan="5" class="no-results">No patients found matching your search.</td>';
                tbody.appendChild(noResultsRow);
            } else if (!show && noResultsRow) {
                noResultsRow.remove();
            }
        }

        // Patient management functions
        function editPatient(patientId) {
            alert('Edit patient: ' + patientId);
            // Add your edit functionality here
        }

        function deletePatient(patientId) {
            if (confirm('Are you sure you want to delete patient ' + patientId + '?')) {
                // Add your delete functionality here
                alert('Patient ' + patientId + ' deleted successfully!');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('filterDropdown');
            const filterBtn = document.querySelector('.filter-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            // Close filter dropdown
            if (!filterBtn.contains(event.target)) {
                dropdown.classList.remove('show');
            }
            
            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                    sidebar.classList.remove('mobile-show');
                    overlay.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>