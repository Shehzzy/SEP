<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Dashboard | VaxPakistan'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Admin Theme: Blue/Purple */
        :root {
            --primary-color: #2980b9;
            --secondary-color: #3498db;
            --sidebar-bg: #2c3e50;
            --light-color: #ebf5fb;
            --text-color: #2c3e50;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: var(--text-color);
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: white;
            padding: 20px 0;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }
        
        .sidebar-header h2 {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            padding-left: 25px;
        }
        
        .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid white;
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 20px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: var(--secondary-color);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-user-shield fa-2x"></i>
                <h2>Admin Dashboard</h2>
                <p style="font-size: 0.9rem; opacity: 0.8;">System Administrator</p>
            </div>
            
            <div class="sidebar-menu">
                <a href="dashboard.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="children.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'children.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> All Children
                </a>
                <a href="bookings.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-check"></i> Approve Requests
                </a>
                <a href="hospitals.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'hospitals.php' ? 'active' : ''; ?>">
                    <i class="fas fa-hospital"></i> Manage Hospitals
                </a>
                <a href="vaccines.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'vaccines.php' ? 'active' : ''; ?>">
                    <i class="fas fa-syringe"></i> Manage Vaccines
                </a>
                <a href="reports.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><?php echo isset($page_title) ? $page_title : 'Admin Dashboard'; ?></h1>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>