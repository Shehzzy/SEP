<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'hospital') {
    header("Location: ../login.php");
    exit();
}
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
            /* Main red - use for buttons, alerts */
            --secondary-color: #c0392b;
            /* Dark red - use for hover states */
            --sidebar-bg: #34495e;
            /* Dark blue-gray sidebar */
            --light-color: #fdedec;
            /* Very light red for backgrounds */
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

        .appointments-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            padding: 15px;
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

        .status-completed {
            background: #d1ecf1;
            color: #0c5460;
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
                <i class="fas fa-hospital fa-2x"></i>
                <h2>Hospital Dashboard</h2>
                <p style="font-size: 0.9rem; opacity: 0.8;">Welcome, <?php echo $_SESSION['name']; ?></p>
            </div>

            <div class="sidebar-menu">
                <a href="dashboard.php" class="menu-item active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="appointments.php" class="menu-item">
                    <i class="fas fa-calendar-check"></i> Appointments
                </a>
                <a href="vaccine_status.php" class="menu-item">
                    <i class="fas fa-syringe"></i> Update Vaccine Status
                </a>
                <a href="inventory.php" class="menu-item">
                    <i class="fas fa-boxes"></i> Vaccine Inventory
                </a>
                <a href="reports.php" class="menu-item">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                <a href="profile.php" class="menu-item">
                    <i class="fas fa-hospital-alt"></i> Hospital Profile
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Hospital Dashboard</h1>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <div class="welcome-box">
                <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
                <p>Manage vaccination appointments and update vaccine status from here.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>15</h3>
                        <p>Today's Appointments</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="stat-info">
                        <h3>8</h3>
                        <p>Vaccinations Completed Today</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>5</h3>
                        <p>Pending Appointments</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3>120</h3>
                        <p>Vaccines in Stock</p>
                    </div>
                </div>
            </div>

            <h2 style="margin-bottom: 20px;">Today's Appointments</h2>
            <div class="appointments-table">
                <table>
                    <thead>
                        <tr>
                            <th>Child Name</th>
                            <th>Vaccine</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ali Ahmed</td>
                            <td>Polio</td>
                            <td>10:00 AM</td>
                            <td><span class="status status-pending">Pending</span></td>
                            <td><a href="#" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Update</a></td>
                        </tr>
                        <tr>
                            <td>Sara Khan</td>
                            <td>Measles</td>
                            <td>11:30 AM</td>
                            <td><span class="status status-approved">Approved</span></td>
                            <td><a href="#" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Update</a></td>
                        </tr>
                        <tr>
                            <td>Ahmed Raza</td>
                            <td>DPT</td>
                            <td>02:00 PM</td>
                            <td><span class="status status-completed">Completed</span></td>
                            <td><a href="#" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2 style="margin-bottom: 20px;">Quick Actions</h2>
            <div class="action-grid">
                <div class="action-card">
                    <i class="fas fa-syringe"></i>
                    <h3>Update Vaccine Status</h3>
                    <p>Mark vaccinations as completed or pending</p>
                    <a href="vaccine_status.php" class="btn">Update Status</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-calendar-day"></i>
                    <h3>View Appointments</h3>
                    <p>Check today's and upcoming appointments</p>
                    <a href="appointments.php" class="btn">View All</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-box"></i>
                    <h3>Check Inventory</h3>
                    <p>View available vaccine stock</p>
                    <a href="inventory.php" class="btn">Check Stock</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Generate Reports</h3>
                    <p>Create vaccination statistics reports</p>
                    <a href="reports.php" class="btn">Generate</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>