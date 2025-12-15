<?php 
include("header.php");
include("../includes/config.php");

$parent_id = $_SESSION['user_id'];

// Get real statistics from database
$children = mysqli_query($conn, "SELECT COUNT(*) as count FROM children WHERE parent_id = '$parent_id'");
$children_count = mysqli_fetch_assoc($children)['count'];

$upcoming = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings b 
                                JOIN children c ON b.child_id = c.child_id 
                                WHERE c.parent_id = '$parent_id' 
                                AND b.status = 0 
                                AND b.booking_date >= CURDATE()");
$upcoming_count = mysqli_fetch_assoc($upcoming)['count'];

$bookings = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings b 
                                JOIN children c ON b.child_id = c.child_id 
                                WHERE c.parent_id = '$parent_id' 
                                AND b.status IN (0,1)");
$bookings_count = mysqli_fetch_assoc($bookings)['count'];

$completed = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings b 
                                 JOIN children c ON b.child_id = c.child_id 
                                 WHERE c.parent_id = '$parent_id' 
                                 AND b.status = 2");
$completed_count = mysqli_fetch_assoc($completed)['count'];
?>
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Parent Dashboard</h1>
                <a href="../logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <div class="welcome-box">
                <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
                <p>Manage your children's vaccination schedule and appointments from here.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $children_count; ?></h3>
                        <p>Children Registered</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-syringe"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $upcoming_count; ?></h3>
                        <p>Upcoming Vaccinations</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $bookings_count; ?></h3>
                        <p>Appointments Booked</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $completed_count; ?></h3>
                        <p>Vaccinations Completed</p>
                    </div>
                </div>
            </div>

            <h2 style="margin-bottom: 20px;">Quick Actions</h2>
            <div class="action-grid">
                <div class="action-card">
                    <i class="fas fa-child"></i>
                    <h3>Add Child</h3>
                    <p>Register your child for vaccination schedule</p>
                    <a href="add_child.php" class="btn">Add New Child</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-calendar-plus"></i>
                    <h3>View Schedule</h3>
                    <p>Check upcoming vaccination dates for your children</p>
                    <a href="children.php" class="btn">My Children</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-hospital"></i>
                    <h3>Book Hospital</h3>
                    <p>Book appointment at nearest vaccination center</p>
                    <a href="vaccination_dates.php" class="btn">Book Now</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-file-download"></i>
                    <h3>Download Reports</h3>
                    <p>Get vaccination history and certificates</p>
                    <a href="reports.php" class="btn">View Reports</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>