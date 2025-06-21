<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Letty's Birthing Home</title>
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
        .search-box {
            position: relative;
        }
        .search-box input {
            border: 1px solid #ddd;
            border-radius: 25px;
            padding: 8px 15px;
            width: 250px;
            font-size: 14px;
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
        .stats-row {
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .stat-card h5 {
            margin: 8px 0 5px 0;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
        }
        .chart-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .chart-card h5 {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        .chart-container {
            height: 280px;
            position: relative;
        }
        .revenue-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .revenue-amount {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .period-controls {
            display: flex;
            justify-content: center;
        }
        .dropdown-row {
            display: flex;
            gap: 15px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        .dropdown-inline {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .dropdown-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
            min-width: fit-content;
        }
        .form-select-sm {
            font-size: 13px;
            padding: 8px 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            background: white;
            color: #495057;
            min-width: 90px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .form-select-sm:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
            outline: none;
        }
        .form-select-sm:hover {
            border-color: #4CAF50;
        }
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .legend-percentage {
            font-weight: 600;
            margin-left: auto;
        }
       

        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 200px;
            }
            .main-content {
                padding: 20px;
            }
            .header {
                left: 0;
                width: 100%;
            }
        }
        
        @media (max-width: 992px) {
            .chart-section {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .stats-row .col-3 {
                width: 50%;
                margin-bottom: 15px;
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
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
            }
            .stats-row {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                gap: 15px !important;
                width: 100% !important;
                max-width: 400px !important;
                margin-bottom: 30px;
            }
            .stats-row .col-3 {
                width: 100% !important;
                max-width: 320px !important;
                margin: 0 !important;
                padding: 0 !important;
                flex: none !important;
            }
            .stat-card {
                height: 100px;
                padding: 15px;
                width: 100%;
            }
            .stat-card h3 {
                font-size: 24px;
            }
            .stat-icon {
                font-size: 20px;
            }
            .chart-section {
                width: 100%;
                max-width: 400px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
            }
            .chart-card {
                width: 100%;
                max-width: 380px;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .chart-container {
                height: 250px;
                width: 100%;
                max-width: 340px;
            }
            .revenue-amount {
                text-align: center;
            }
            .revenue-label {
                text-align: center;
            }
            .dropdown-row {
                flex-direction: column;
                gap: 10px;
            }
            .dropdown-inline {
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .header h4 {
                font-size: 14px;
            }
            .main-content {
                padding: 10px;
            }
            .stats-row {
                max-width: 350px !important;
            }
            .stats-row .col-3 {
                max-width: 300px !important;
            }
            .stat-card {
                height: 90px;
                padding: 10px;
            }
            .stat-card h3 {
                font-size: 20px;
            }
            .stat-card h5 {
                font-size: 11px;
            }
            .chart-section {
                max-width: 350px;
            }
            .chart-card {
                max-width: 330px;
                padding: 15px;
            }
            .chart-container {
                height: 200px;
                max-width: 300px;
            }
            .chart-card h5 {
                font-size: 14px;
                text-align: center;
            }
            .revenue-amount {
                font-size: 20px;
            }
            .dropdown-row {
                flex-direction: column;
                gap: 8px;
            }
            .form-select-sm {
                min-width: 85px;
                font-size: 12px;
                padding: 6px 10px;
            }
            .dropdown-label {
                font-size: 12px;
            }
        }
        
        @media (max-width: 360px) {
            .main-content {
                padding: 8px;
            }
            .stats-row {
                max-width: 320px !important;
            }
            .stats-row .col-3 {
                max-width: 280px !important;
            }
            .chart-section {
                max-width: 320px;
            }
            .chart-card {
                max-width: 300px;
                padding: 12px;
            }
            .chart-container {
                height: 180px;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" onclick="toggleMobileMenu()"></div>
    <div class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <img src="{{ asset('img/imglogo.png') }}" alt="Logo">
            </div>
            <h5>Letty's Birthing Home</h5>
        </div>
       <a href="{{ route('dashboards') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('patients') }}"><i class="fas fa-users"></i> Patients</a>
        <a href="{{ route('appointments') }}" ><i class="fas fa-calendar"></i> Appointments</a>
        <a href="{{ route('staffs') }}"><i class="fas fa-user-nurse"></i> Staff</a>
        <a href="{{ route('medications') }}"><i class="fas fa-pills"></i> Medication</a>
        <a href="{{ route('reports') }}"><i class="fas fa-file-alt"></i> Reports</a>
        <a href="{{ route('settings') }}"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Admin Dashboard</h4>
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
            <div class="row stats-row">
                <div class="col-3">
                    <div class="stat-card">
                        <i class="fas fa-users stat-icon text-success"></i>
                        <h5>Total Patients</h5>
                        <h3>69</h3>
                    </div>
                </div>
                <div class="col-3">
                    <div class="stat-card">
                        <i class="fas fa-calendar-check stat-icon text-primary"></i>
                        <h5>Appointments</h5>
                        <h3>12</h3>
                    </div>
                </div>
                <div class="col-3">
                    <div class="stat-card">
                        <i class="fas fa-clock stat-icon text-warning"></i>
                        <h5>Pending Actions</h5>
                        <h3>7</h3>
                    </div>
                </div>
                <div class="col-3">
                    <div class="stat-card">
                        <i class="fas fa-file-alt stat-icon text-info"></i>
                        <h5>Reports</h5>
                        <h3>5</h3>
                    </div>
                </div>
            </div>

            <div class="chart-section">
                <div class="chart-card">
                    <h5>Monthly Revenue</h5>
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="revenue-amount">₱ 145,000</div>
                        <div class="revenue-label" style="font-size: 13px; color: #6c757d; margin-bottom: 15px;">From April to May 2025</div>
                        <div class="period-controls">
                            <div class="dropdown-row">
                                <div class="dropdown-inline">
                                    <label class="dropdown-label">From:</label>
                                    <select class="form-select form-select-sm" id="fromMonth">
                                        <option value="0">January</option>
                                        <option value="1">February</option>
                                        <option value="2">March</option>
                                        <option value="3" selected>April</option>
                                        <option value="4">May</option>
                                        <option value="5">June</option>
                                        <option value="6">July</option>
                                        <option value="7">August</option>
                                        <option value="8">September</option>
                                        <option value="9">October</option>
                                        <option value="10">November</option>
                                        <option value="11">December</option>
                                    </select>
                                </div>
                                <div class="dropdown-inline">
                                    <label class="dropdown-label">To:</label>
                                    <select class="form-select form-select-sm" id="toMonth">
                                        <option value="0">January</option>
                                        <option value="1">February</option>
                                        <option value="2">March</option>
                                        <option value="3">April</option>
                                        <option value="4" selected>May</option>
                                        <option value="5">June</option>
                                        <option value="6">July</option>
                                        <option value="7">August</option>
                                        <option value="8">September</option>
                                        <option value="9">October</option>
                                        <option value="10">November</option>
                                        <option value="11">December</option>
                                    </select>
                                </div>
                                <div class="dropdown-inline">
                                    <label class="dropdown-label">Year:</label>
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
                <div class="chart-card">
                    <h5>Service Breakdown</h5>
                    <div class="chart-container">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #4CAF50;"></div>
                            <span>Full Service</span>
                            <span class="legend-percentage">50%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #FF9800;"></div>
                            <span>Partial Service</span>
                            <span class="legend-percentage">31%</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #2196F3;"></div>
                            <span>Consultation Only</span>
                            <span class="legend-percentage">19%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue data for different years
        const revenueData = {
            2023: [78000, 85000, 72000, 95000, 108000, 125000, 112000, 118000, 105000, 128000, 139000, 152000],
            2024: [82000, 89000, 76000, 98000, 115000, 135000, 122000, 125000, 110000, 135000, 145000, 158000],
            2025: [85000, 92000, 78000, 105000, 118000, 145000, 132000, 128000, 115000, 138000, 149000, 162000],
            2026: [88000, 95000, 82000, 108000, 122000, 148000, 135000, 132000, 118000, 142000, 152000, 165000],
            2027: [91000, 98000, 85000, 112000, 125000, 152000, 138000, 135000, 122000, 145000, 155000, 168000]
        };

        let currentChart;

        // Bar Chart - Monthly Revenue
        function createBarChart(year = 2025) {
            const ctxBar = document.getElementById('barChart').getContext('2d');
            
            if (currentChart) {
                currentChart.destroy();
            }
            
            currentChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: revenueData[year],
                        backgroundColor: [
                            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FCEA2B', '#FF9FF3',
                            '#A8E6CF', '#FFD93D', '#6BCF7F', '#4D96FF', '#9B59B6', '#E67E22'
                        ],
                        borderRadius: 4,
                        borderSkipped: false,
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
                                    return 'Revenue: ₱' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0'
                            },
                            ticks: {
                                color: '#666',
                                callback: function(value) {
                                    return '₱' + (value / 1000) + 'k';
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

        // Initialize chart
        createBarChart();

        // Pie Chart
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Full Service', 'Partial Service', 'Consultation Only'],
                datasets: [{
                    data: [50, 31, 19],
                    backgroundColor: ['#4CAF50', '#FF9800', '#2196F3'],
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
                    }
                }
            }
        });

        // Mobile menu functionality
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }

        // Revenue calculation functionality
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        function updateRevenue() {
            const fromMonth = parseInt(document.getElementById('fromMonth').value);
            const toMonth = parseInt(document.getElementById('toMonth').value);
            const selectedYear = parseInt(document.getElementById('yearSelect').value);
            const revenueAmount = document.querySelector('.revenue-amount');
            const revenueLabel = document.querySelector('.revenue-label');
            
            // Update chart with selected year
            createBarChart(selectedYear);
            
            let totalRevenue = 0;
            let startMonth = Math.min(fromMonth, toMonth);
            let endMonth = Math.max(fromMonth, toMonth);
            
            const yearData = revenueData[selectedYear];
            for (let i = startMonth; i <= endMonth; i++) {
                totalRevenue += yearData[i];
            }
            
            revenueAmount.textContent = '₱ ' + totalRevenue.toLocaleString();
            revenueLabel.textContent = `From ${monthNames[startMonth]} to ${monthNames[endMonth]} ${selectedYear}`;
        }
        
        // Add event listeners for dropdowns
        document.getElementById('fromMonth').addEventListener('change', updateRevenue);
        document.getElementById('toMonth').addEventListener('change', updateRevenue);
        document.getElementById('yearSelect').addEventListener('change', updateRevenue);

        // Close mobile menu when clicking on menu items
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMobileMenu();
                }
            });
        });
    </script>
</body>
</html>