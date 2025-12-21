<?php
require_once 'header.php';

// Get date range for report
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Report 1: Daily vaccinations
$daily_query = "SELECT DATE(booking_date) as date, 
               COUNT(*) as total_appointments,
               SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed
               FROM bookings 
               WHERE hospital_id = '$hospital_id'
               AND booking_date BETWEEN '$start_date' AND '$end_date'
               GROUP BY DATE(booking_date)
               ORDER BY date DESC";
$daily_result = mysqli_query($conn, $daily_query);

// Report 2: Vaccine-wise report
$vaccine_query = "SELECT v.vaccine_name, 
                 COUNT(b.booking_id) as total_bookings,
                 SUM(CASE WHEN b.status = 2 THEN 1 ELSE 0 END) as completed
                 FROM bookings b
                 JOIN vaccines v ON b.vaccine_id = v.vaccine_id
                 WHERE b.hospital_id = '$hospital_id'
                 AND b.booking_date BETWEEN '$start_date' AND '$end_date'
                 GROUP BY v.vaccine_id
                 ORDER BY total_bookings DESC";
$vaccine_result = mysqli_query($conn, $vaccine_query);

// Report 3: Status summary
$status_query = "SELECT 
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as vaccinated,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as rejected,
                COUNT(*) as total
                FROM bookings 
                WHERE hospital_id = '$hospital_id'
                AND booking_date BETWEEN '$start_date' AND '$end_date'";
$status_result = mysqli_query($conn, $status_query);
$status_data = mysqli_fetch_assoc($status_result);
?>

<div class="welcome-box">
    <h2>Vaccination Reports</h2>
    <p>Generate and view vaccination statistics</p>
</div>

<!-- Date Filter -->
<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
        <div>
            <label>Start Date:</label>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" required>
        </div>
        <div>
            <label>End Date:</label>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" required>
        </div>
        <button type="submit" class="btn">Generate Report</button>
        <a href="reports.php" class="btn btn-secondary">Reset</a>
    </form>
</div>

<!-- Summary Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $status_data['total'] ?? 0; ?></h3>
            <p>Total Appointments</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-syringe"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $status_data['vaccinated'] ?? 0; ?></h3>
            <p>Vaccinations Completed</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $status_data['pending'] ?? 0; ?></h3>
            <p>Pending</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $status_data['total'] > 0 ? 
                round(($status_data['vaccinated'] / $status_data['total']) * 100, 1) : 0; ?>%</h3>
            <p>Completion Rate</p>
        </div>
    </div>
</div>

<!-- Daily Report -->
<h3 style="margin: 30px 0 15px;">Daily Vaccination Report</h3>
<div style="background: white; border-radius: 10px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Appointments</th>
                <th>Vaccinations Completed</th>
                <th>Completion Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($daily_result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($daily_result)): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['date'])); ?></td>
                        <td><?php echo $row['total_appointments']; ?></td>
                        <td><?php echo $row['completed']; ?></td>
                        <td>
                            <?php 
                            $rate = $row['total_appointments'] > 0 ? 
                                round(($row['completed'] / $row['total_appointments']) * 100, 1) : 0;
                            echo $rate . '%';
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty-state">
                        <i class="fas fa-chart-bar"></i>
                        <p>No data found for selected date range</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Vaccine-wise Report -->
<h3 style="margin: 30px 0 15px;">Vaccine-wise Report</h3>
<div style="background: white; border-radius: 10px; overflow: hidden; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <table>
        <thead>
            <tr>
                <th>Vaccine Name</th>
                <th>Total Bookings</th>
                <th>Completed</th>
                <th>Pending</th>
                <th>Completion Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($vaccine_result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($vaccine_result)): ?>
                    <?php 
                    $pending = $row['total_bookings'] - $row['completed'];
                    $rate = $row['total_bookings'] > 0 ? 
                        round(($row['completed'] / $row['total_bookings']) * 100, 1) : 0;
                    ?>
                    <tr>
                        <td><?php echo $row['vaccine_name']; ?></td>
                        <td><?php echo $row['total_bookings']; ?></td>
                        <td><?php echo $row['completed']; ?></td>
                        <td><?php echo $pending; ?></td>
                        <td><?php echo $rate; ?>%</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="empty-state">
                        <i class="fas fa-syringe"></i>
                        <p>No vaccine data found</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Export Options -->
<div style="text-align: center; margin-top: 30px;">
    <button class="btn" onclick="printReport()">
        <i class="fas fa-print"></i> Print Report
    </button>
    <button class="btn btn-secondary" onclick="exportToCSV()">
        <i class="fas fa-download"></i> Export as CSV
    </button>
</div>

<script>
function printReport() {
    window.print();
}

function exportToCSV() {
    alert('CSV export feature would be implemented here.\nFor now, you can print the report.');
}
</script>