<?php
require_once 'header.php';

// Simple query - hospital only sees approved or vaccinated appointments
$sql = "SELECT b.*, c.child_name, v.vaccine_name
        FROM bookings b
        JOIN children c ON b.child_id = c.child_id
        JOIN vaccines v ON b.vaccine_id = v.vaccine_id
        WHERE b.hospital_id = '$hospital_id'
        AND b.status >= 1
        ORDER BY b.booking_date DESC
        LIMIT 5";

$result = mysqli_query($conn, $sql);
?>

<!-- Welcome Box -->
<div class="welcome-box">
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $today_count; ?></h3>
            <p>Today's Appointments</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-syringe"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $completed_today; ?></h3>
            <p>Completed Today</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $pending_count; ?></h3>
            <p>Pending</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $vaccine_stock; ?></h3>
            <p>In Stock</p>
        </div>
    </div>
</div>

<!-- Recent Appointments -->
<h2 style="margin-bottom: 20px;">Recent Appointments</h2>
<div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <table>
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Vaccine</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['child_name']; ?></td>
                        <td><?php echo $row['vaccine_name']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></td>
                        <td>
                            <?php 
                            if($row['status'] == 1) {
                                echo '<span class="status status-approved">Approved</span>';
                            } else {
                                echo '<span class="status status-vaccinated">Vaccinated</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 1): ?>
                                <a href="update_status.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">
                                    Update
                                </a>
                            <?php else: ?>
                                <span class="btn" style="background: #95a5a6; padding: 5px 10px; font-size: 0.8rem;">
                                    Done
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; color: #bdc3c7; margin-bottom: 10px;"></i>
                        <p>No appointments found</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Quick Actions -->
<h2 style="margin: 30px 0 20px;">Quick Actions</h2>
<div class="action-grid">
    <div class="action-card">
        <i class="fas fa-syringe"></i>
        <h3>Update Status</h3>
        <a href="update_status.php" class="btn">Update</a>
    </div>

    <div class="action-card">
        <i class="fas fa-calendar-day"></i>
        <h3>Appointments</h3>
        <a href="appointments.php" class="btn">View</a>
    </div>

    <div class="action-card">
        <i class="fas fa-box"></i>
        <h3>Inventory</h3>
        <a href="inventory.php" class="btn">Check</a>
    </div>

    <!-- <div class="action-card">
        <i class="fas fa-chart-line"></i>
        <h3>Reports</h3>
        <a href="reports.php" class="btn">View</a>
    </div> -->
</div>