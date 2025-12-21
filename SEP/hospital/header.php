<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // This is CORRECT - user_id from session

// IMPORTANT: Get hospital_id from database, NOT from session['user_id']
$query = "SELECT hospital_id, hospital_name FROM hospitals WHERE user_id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // No hospital found for this user
    echo "<div class='alert alert-error'>No hospital found for your account. Please contact administrator.</div>";
    $hospital_id = 0;
    $hospital_name = "Unknown Hospital";
} else {
    $hospital_data = mysqli_fetch_assoc($result);
    $hospital_id = $hospital_data['hospital_id'];    // CORRECT: From database
    $hospital_name = $hospital_data['hospital_name']; // CORRECT: From database
    
    // Store in session for future use
    $_SESSION['hospital_id'] = $hospital_id;
    $_SESSION['hospital_name'] = $hospital_name;
}


// Get hospital stats for sidebar
$today = date('Y-m-d');

// Today's appointments
// Should be (CORRECT - only approved appointments):
$today_sql = "SELECT COUNT(*) as count FROM bookings 
              WHERE hospital_id = '$hospital_id' 
              AND status = 1
              AND booking_date = '$today'";
$today_result = mysqli_query($conn, $today_sql);
$today_count = mysqli_fetch_assoc($today_result)['count'];

// Completed today
$completed_sql = "SELECT COUNT(*) as count FROM bookings 
                  WHERE hospital_id = '$hospital_id' 
                  AND status = 2 AND booking_date = '$today'";
$completed_result = mysqli_query($conn, $completed_sql);
$completed_today = mysqli_fetch_assoc($completed_result)['count'];

// Pending appointments
$pending_sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE hospital_id = '$hospital_id' 
                AND status = 1  
                AND booking_date >= '$today' 
                AND booking_id NOT IN (
                    SELECT booking_id FROM vaccination_reports
                )"; // Not yet vaccinated
$pending_result = mysqli_query($conn, $pending_sql);
$pending_count = mysqli_fetch_assoc($pending_result)['count'];

// Total vaccines in stock (if inventory table exists)
$inventory_sql = "SELECT SUM(quantity) as total FROM hospital_vaccines 
                  WHERE hospital_id = '$hospital_id'";
$inventory_result = mysqli_query($conn, $inventory_sql);
$vaccine_stock = mysqli_fetch_assoc($inventory_result)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard | VaxPakistan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Hospital Theme: Red/Orange */
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #c0392b;
            --sidebar-bg: #34495e;
            --light-color: #fdedec;
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 0;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
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
            background: rgba(255, 255, 255, 0.1);
            padding-left: 25px;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
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
            background: #2c3e50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .logout-btn:hover {
            background: #1a252f;
        }

        /* Common styles for all pages */
        .welcome-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border-left: 5px solid var(--primary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--light-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
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
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--secondary-color);
        }

        .btn-secondary {
            background: #7f8c8d;
        }

        .btn-secondary:hover {
            background: #6c7b7d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background: var(--light-color);
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
            border-bottom: 2px solid #eee;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-vaccinated {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .action-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s;
            border-top: 4px solid var(--primary-color);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--text-color);
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #bdc3c7;
        }

        .filter-bar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-hospital fa-2x"></i>
                <h2><?php echo $hospital_name; ?></h2>

                <p style="font-size: 0.9rem; opacity: 0.8;">Hospital Dashboard</p>
            </div>

            <div class="sidebar-menu">
                <a href="dashboard.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="appointments.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'appointments.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i> Appointments
                </a>
                <!-- <a href="update_status.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'update_status.php' ? 'active' : ''; ?>">
                    <i class="fas fa-syringe"></i> Update Vaccine Status
                </a> -->
                <a href="inventory.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : ''; ?>">
                    <i class="fas fa-boxes"></i> Vaccine Inventory
                </a>
                <!-- <a href="reports.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i> Reports
                </a> -->
                <a href="profile.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-hospital-alt"></i> Hospital Profile
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>
                    <?php 
                    $page_titles = [
                        'dashboard.php' => 'Dashboard',
                        'appointments.php' => 'Appointments',
                        'update_status.php' => 'Update Vaccine Status',
                        'inventory.php' => 'Vaccine Inventory',
                        'reports.php' => 'Reports',
                        'profile.php' => 'Hospital Profile'
                    ];
                    echo $page_titles[basename($_SERVER['PHP_SELF'])] ?? 'Hospital Dashboard';
                    ?>
                </h1>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>