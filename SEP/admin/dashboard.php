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
    <title>Admin Dashboard | VaxPakistan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Admin Theme: Purple/Indigo */
        :root {
            --primary-color: #2980b9;
            /* Main blue - use for buttons, highlights */
            --secondary-color: #3498db;
            /* Light blue - use for hover states */
            --sidebar-bg: #2c3e50;
            /* Dark blue sidebar */
            --light-color: #ebf5fb;
            /* Very light blue for backgrounds */
            --text-color: #2c3e50;
            /* Dark blue for text */
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

        .recent-activity {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 30px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: var(--light-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
        }

        .activity-content p {
            margin: 0;
            font-size: 0.9rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #999;
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
            box-shadow: 0 8px 25px rgba(155, 89, 182, 0.1);
        }

        .action-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .action-card h3 {
            margin-bottom: 10px;
            color: var(--text-color);
        }

        .action-card p {
            color: #666;
            margin-bottom: 20px;
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
        }

        .btn:hover {
            background: var(--dark-color);
        }

        .logout-btn {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }

        .logout-btn:hover {
            background: #1a252f;
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
                <a href="dashboard.php" class="menu-item active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="children.php" class="menu-item">
                    <i class="fas fa-users"></i> All Children
                </a>
                <a href="vaccination_dates.php" class="menu-item">
                    <i class="fas fa-calendar-alt"></i> Vaccination Dates
                </a>
                <a href="vaccines.php" class="menu-item">
                    <i class="fas fa-syringe"></i> Manage Vaccines
                </a>
                <a href="requests.php" class="menu-item">
                    <i class="fas fa-clipboard-check"></i> Approve Requests
                </a>
                <a href="hospitals.php" class="menu-item">
                    <i class="fas fa-hospital"></i> Manage Hospitals
                </a>
                <a href="bookings.php" class="menu-item">
                    <i class="fas fa-calendar-check"></i> Booking Details
                </a>
                <a href="reports.php" class="menu-item">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <div class="welcome-box">
                <h2>Welcome, System Administrator!</h2>
                <p>Manage the entire vaccination system, users, hospitals, and requests from here.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>256</h3>
                        <p>Total Children</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="stat-info">
                        <h3>24</h3>
                        <p>Registered Hospitals</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-info">
                        <h3>18</h3>
                        <p>Pending Requests</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="stat-info">
                        <h3>12</h3>
                        <p>Vaccine Types</p>
                    </div>
                </div>
            </div>

            <h2 style="margin-bottom: 20px;">Recent Activity</h2>
            <div class="recent-activity">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>New parent registered:</strong> Ahmed Raza</p>
                        <div class="activity-time">2 minutes ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>Hospital registration approved:</strong> Aga Khan Hospital</p>
                        <div class="activity-time">15 minutes ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>Vaccination completed:</strong> Ali Khan (Polio Vaccine)</p>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>New appointment booked:</strong> Lahore General Hospital</p>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
            </div>

            <h2 style="margin-bottom: 20px;">Quick Actions</h2>
            <div class="action-grid">
                <div class="action-card">
                    <i class="fas fa-hospital-plus"></i>
                    <h3>Add Hospital</h3>
                    <p>Register new vaccination center</p>
                    <a href="add_hospital.php" class="btn">Add New</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-clipboard-check"></i>
                    <h3>Approve Requests</h3>
                    <p>Review and approve pending requests</p>
                    <a href="requests.php" class="btn">View Requests</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-syringe"></i>
                    <h3>Manage Vaccines</h3>
                    <p>Add/update vaccine availability</p>
                    <a href="vaccines.php" class="btn">Manage</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-chart-pie"></i>
                    <h3>Generate Reports</h3>
                    <p>Create system-wide reports</p>
                    <a href="reports.php" class="btn">Generate</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>