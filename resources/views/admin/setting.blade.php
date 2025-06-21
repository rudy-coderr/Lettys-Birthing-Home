<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Letty's Birthing Home</title>
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
        .settings-nav {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .settings-nav .nav-tabs {
            border: none;
        }
        .settings-nav .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            margin-right: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .settings-nav .nav-link.active {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }
        .settings-nav .nav-link:hover:not(.active) {
            background: #f8f9fa;
            color: #495057;
        }
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .settings-card h5 {
            margin-bottom: 20px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .settings-card h5 i {
            color: #4CAF50;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
            outline: none;
        }
        .btn-primary {
           background: linear-gradient(135deg,rgb(85, 128, 86),rgb(63, 126, 66));
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
           background: linear-gradient(135deg, rgb(47, 184, 52), rgb(37, 167, 41));
           
           
        }
        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e9ecef;
             padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #e9ecef;
            color: #495057;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .3s;
            border-radius: 28px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #4CAF50;
        }
        input:checked + .slider:before {
            transform: translateX(22px);
        }
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .setting-item:last-child {
            border-bottom: none;
        }
        .setting-item-info h6 {
            margin: 0;
            font-weight: 500;
            color: #2c3e50;
        }
        .setting-item-info p {
            margin: 0;
            font-size: 13px;
            color: #6c757d;
        }
        .alert {
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
        }
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1, #b8daff);
            color: #0c5460;
        }
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
        }
        .tab-content .tab-pane {
            display: none;
        }
        .tab-content .tab-pane.active {
            display: block;
        }
        
        /* Logo Preview Styles */
        .logo-preview-container {
            transition: all 0.3s ease;
        }
        .logo-preview-container:hover {
            border-color: #4CAF50;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
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
            .settings-card {
                padding: 20px;
            }
            .settings-nav .nav-link {
                padding: 8px 12px;
                margin-right: 5px;
                font-size: 13px;
            }
        }
        
        @media (max-width: 480px) {
            .settings-nav .nav-tabs {
                flex-wrap: wrap;
            }
            .settings-nav .nav-link {
                margin-bottom: 5px;
            }
            .setting-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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
       <a href="{{ route('dashboards') }}" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('patients') }}"><i class="fas fa-users"></i> Patients</a>
        <a href="{{ route('appointments') }}" ><i class="fas fa-calendar"></i> Appointments</a>
        <a href="{{ route('staffs') }}"><i class="fas fa-user-nurse"></i> Staff</a>
        <a href="{{ route('medications') }}"><i class="fas fa-pills"></i> Medication</a>
        <a href="{{ route('reports') }}"><i class="fas fa-file-alt"></i> Reports</a>
        <a href="{{ route('settings') }} class="active""><i class="fas fa-cog"></i> Settings</a>
    </div>

    <div class="content">
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn me-3" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4>Settings</h4>
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
            <div class="settings-nav">
                <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" onclick="showTab('general')">
                            <i class="fas fa-cog me-2"></i>General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="showTab('notifications')">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="showTab('security')">
                            <i class="fas fa-shield-alt me-2"></i>Security
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="settingsTabContent">
                <!-- General Settings -->
                <div class="tab-pane active" id="general" role="tabpanel">
                    <div class="settings-card">
                        <h5><i class="fas fa-image"></i>System Logo</h5>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="current-logo-preview">
                                   <div class="logo-preview-container" style="width: 120px; height: 120px; border: 2px dashed #e9ecef; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; position: relative; overflow: hidden;">
                                        <div id="currentLogoPreview" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <img src="{{ asset('img/imglogo.png') }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    </div>

                                   
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="form-label">Upload New Logo</label>
                                    <input type="file" class="form-control" id="logoUpload" accept="image/*" onchange="previewLogo(event)">
                                    <small class="form-text text-muted">Recommended size: 200x200px. Supported formats: JPG, PNG, SVG</small>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="uploadLogo()">
                                        <i class="fas fa-upload me-1"></i>Update Logo
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="resetLogo()">
                                        <i class="fas fa-undo me-1"></i>Reset to Default
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h5><i class="fas fa-hospital"></i>Facility Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Facility Name</label>
                                    <input type="text" class="form-control" value="Letty's Birthing Home">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">License Number</label>
                                    <input type="text" class="form-control" value="LBH-2024-001">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="3">123 Birthing Street, Legaspi City, Bicol Region, Philippines</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" value="+63 123 456 7890">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="info@lettysbirthinghome.ph">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h5><i class="fas fa-clock"></i>Operating Hours</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Opening Time</label>
                                    <input type="time" class="form-control" value="08:00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Closing Time</label>
                                    <input type="time" class="form-control" value="18:00">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Emergency Hours</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="emergency24" checked>
                                <label class="form-check-label" for="emergency24">
                                    24/7 Emergency Services Available
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h5><i class="fas fa-globe"></i>Localization</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Language</label>
                                    <select class="form-select">
                                        <option selected>English</option>
                                        <option>Filipino</option>
                                        <option>Bikol</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Timezone</label>
                                    <select class="form-select">
                                        <option selected>Asia/Manila (UTC+8)</option>
                                        <option>UTC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date Format</label>
                                    <select class="form-select">
                                        <option>MM/DD/YYYY</option>
                                        <option selected>DD/MM/YYYY</option>
                                        <option>YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select">
                                        <option selected>Philippine Peso (₱)</option>
                                        <option>US Dollar ($)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" onclick="saveSettings('general')">Save Changes</button>
                        <button type="button" class="btn btn-secondary" onclick="resetSettings('general')">Reset to Default</button>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div class="tab-pane" id="notifications" role="tabpanel">
                    <div class="settings-card">
                        <h5><i class="fas fa-envelope"></i>Email Notifications</h5>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Appointment Reminders</h6>
                                <p>Send email reminders to patients before appointments</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>New Patient Registration</h6>
                                <p>Notify staff when new patients register</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>System Updates</h6>
                                <p>Receive notifications about system updates and maintenance</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                   
                    <div class="settings-card">
                        <h5><i class="fas fa-cog"></i>Notification Settings</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Reminder Time (hours before)</label>
                                    <select class="form-select">
                                        <option>1 hour</option>
                                        <option>2 hours</option>
                                        <option>6 hours</option>
                                        <option selected>24 hours</option>
                                        <option>48 hours</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Quiet Hours (No notifications)</label>
                                    <select class="form-select">
                                        <option>None</option>
                                        <option selected>10 PM - 6 AM</option>
                                        <option>11 PM - 7 AM</option>
                                        <option>9 PM - 8 AM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="saveSettings('notifications')">Save Notification Settings</button>
                </div>

                 <!-- Security Settings -->
                <div class="tab-pane" id="security" role="tabpanel">
                    <div class="settings-card">
                        <h5><i class="fas fa-key"></i>Password Policy</h5>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Minimum Password Length</h6>
                                <p>Set minimum number of characters required</p>
                            </div>
                            <select class="form-select" style="width: 120px;">
                                <option>6</option>
                                <option selected>8</option>
                                <option>10</option>
                                <option>12</option>
                            </select>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Require Special Characters</h6>
                                <p>Passwords must contain at least one special character</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Password Expiry</h6>
                                <p>Force users to change passwords periodically</p>
                            </div>
                            <select class="form-select" style="width: 150px;">
                                <option>Never</option>
                                <option>30 days</option>
                                <option selected>90 days</option>
                                <option>180 days</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h5><i class="fas fa-lock"></i>Session Management</h5>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Session Timeout</h6>
                                <p>Automatically log out inactive users after</p>
                            </div>
                            <select class="form-select" style="width: 150px;">
                                <option>15 minutes</option>
                                <option selected>30 minutes</option>
                                <option>1 hour</option>
                                <option>2 hours</option>
                                <option>Never</option>
                            </select>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Remember Login</h6>
                                <p>Allow users to stay logged in across sessions</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h5><i class="fas fa-shield-alt"></i>Two-Factor Authentication</h5>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Two-factor authentication is highly recommended for admin accounts.
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Enable 2FA for Admin</h6>
                                <p>Require two-factor authentication for administrator accounts</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Enable 2FA for Staff</h6>
                                <p>Require two-factor authentication for all staff accounts</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-card">
                        <h5><i class="fas fa-history"></i>Access Logs</h5>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Log User Activity</h6>
                                <p>Keep detailed logs of user login and system access</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="setting-item">
                            <div class="setting-item-info">
                                <h6>Log Retention Period</h6>
                                <p>How long to keep access logs</p>
                            </div>
                            <select class="form-select" style="width: 150px;">
                                <option>30 days</option>
                                <option selected>90 days</option>
                                <option>180 days</option>
                                <option>1 year</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="saveSettings('security')">Save Security Settings</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('mobile-show');
            overlay.classList.toggle('show');
        }

        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tab panes
            const tabPanes = document.querySelectorAll('.tab-pane');
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Remove active class from all nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            
            // Show selected tab pane
            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
            
            // Add active class to clicked nav link
            event.target.classList.add('active');
        }

        // Save settings functionality
        function saveSettings(settingType) {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Saving...';
            button.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Reset button state
                button.textContent = originalText;
                button.disabled = false;
                
                // Show success message
                showNotification(`${settingType.charAt(0).toUpperCase() + settingType.slice(1)} settings saved successfully!`, 'success');
            }, 1500);
        }

        // Reset settings functionality
        function resetSettings(settingType) {
            if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                // Reset form values based on setting type
                if (settingType === 'general') {
                    // Reset general settings to default
                    document.querySelector('input[type="text"]').value = "Letty's Birthing Home";
                    document.querySelector('input[type="email"]').value = "info@lettysbirthinghome.ph";
                    // Add more reset logic as needed
                }
                
                showNotification(`${settingType.charAt(0).toUpperCase() + settingType.slice(1)} settings reset to default values.`, 'info');
            }
        }

        // Notification system
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border-radius: 8px;
                animation: slideIn 0.3s ease;
            `;
            
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Add CSS animation for notifications
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenuBtn.contains(event.target) && 
                sidebar.classList.contains('mobile-show')) {
                toggleMobileMenu();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.remove('mobile-show');
                overlay.classList.remove('show');
            }
        });

        // Initialize tooltips if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Add any initialization code here
            console.log('Letty\'s Birthing Home Settings Page Loaded');
        });
    </script>
</body>
</html>