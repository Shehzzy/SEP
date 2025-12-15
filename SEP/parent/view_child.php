<?php 
include("header.php");
include("../includes/config.php");

$child_id = $_GET['child_id'] ?? 0;
$parent_id = $_SESSION['user_id'];

// Get child details
$sql = "SELECT * FROM children WHERE child_id = '$child_id' AND parent_id = '$parent_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='main-content'><div class='header'><h1>Child Not Found</h1></div>";
    echo "<p>Child not found or you don't have permission to view.</p>";
    echo "<a href='children.php' class='btn'>Back to Children</a></div>";
    include("footer.php");
    exit();
}

$child = mysqli_fetch_assoc($result);

// Calculate age
$birth = new DateTime($child['dob']);
$today = new DateTime();
$age_years = $birth->diff($today)->y;
$age_months = $birth->diff($today)->m;
$age_days = $birth->diff($today)->d;

// Get vaccination stats
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 0 AND booking_date >= CURDATE() THEN 1 ELSE 0 END) as upcoming,
    SUM(CASE WHEN status = 0 AND booking_date < CURDATE() THEN 1 ELSE 0 END) as overdue
    FROM bookings WHERE child_id = '$child_id'";
$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);
?>

<div class="main-content">
    <div class="header">
        <h1>Child Details</h1>
        <div>
            <a href="children.php" class="btn" style="background: #95a5a6;">Back to Children</a>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="child-detail-container">
        <div class="child-info-card">
            <div class="child-header">
                <div class="child-icon-large">
                    <i class="fas fa-child"></i>
                </div>
                <div class="child-title">
                    <h2><?php echo htmlspecialchars($child['child_name']); ?></h2>
                    <p class="child-age">Age: <?php echo $age_years; ?> years, <?php echo $age_months; ?> months, <?php echo $age_days; ?> days</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value"><?php echo date('d M Y', strtotime($child['dob'])); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Gender:</span>
                    <span class="info-value"><?php echo $child['gender']; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Weight:</span>
                    <span class="info-value"><?php echo $child['weight'] ? $child['weight'] . ' kg' : 'Not specified'; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Blood Group:</span>
                    <span class="info-value"><?php echo $child['blood_group'] ?: 'Not specified'; ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Registered On:</span>
                    <span class="info-value"><?php echo date('d M Y, h:i A', strtotime($child['created_at'])); ?></span>
                </div>
            </div>
        </div>

        <div class="vaccination-stats">
            <h3>Vaccination Summary</h3>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number"><?php echo $stats['total']; ?></div>
                    <div class="stat-label">Total Vaccines</div>
                </div>
                
                <div class="stat-box completed">
                    <div class="stat-number"><?php echo $stats['completed']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                
                <div class="stat-box upcoming">
                    <div class="stat-number"><?php echo $stats['upcoming']; ?></div>
                    <div class="stat-label">Upcoming</div>
                </div>
                
                <div class="stat-box overdue">
                    <div class="stat-number"><?php echo $stats['overdue']; ?></div>
                    <div class="stat-label">Overdue</div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="vaccination_dates.php?child_id=<?php echo $child_id; ?>" class="action-btn">
                <i class="fas fa-calendar-alt"></i>
                <span>View Vaccination Schedule</span>
            </a>
            
            <a href="book_hospital.php?child_id=<?php echo $child_id; ?>" class="action-btn">
                <i class="fas fa-hospital"></i>
                <span>Book Appointment</span>
            </a>
            
            <a href="reports.php?child_id=<?php echo $child_id; ?>" class="action-btn">
                <i class="fas fa-file-medical"></i>
                <span>View Vaccination Report</span>
            </a>
        </div>

        <div class="recent-vaccinations">
            <h3>Recent Vaccinations</h3>
            <?php
            $recent_sql = "SELECT v.vaccine_name, b.booking_date, b.status 
                          FROM bookings b 
                          JOIN vaccines v ON b.vaccine_id = v.vaccine_id 
                          WHERE b.child_id = '$child_id' 
                          ORDER BY b.booking_date ASC 
                          LIMIT 5";
            $recent_result = mysqli_query($conn, $recent_sql);
            
            if (mysqli_num_rows($recent_result) > 0): ?>
            <table class="vaccine-table">
                <tr>
                    <th>Vaccine</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($recent_result)): 
                    $status = $row['status'];
                    $status_text = ['Pending', 'Approved', 'Vaccinated', 'Rejected'][$status];
                    $status_class = ['pending', 'approved', 'completed', 'rejected'][$status];
                ?>
                <tr>
                    <td><?php echo $row['vaccine_name']; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['booking_date'])); ?></td>
                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p class="no-data">No vaccination records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.child-detail-container {
    max-width: 1000px;
    margin: 0 auto;
}

.child-info-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.child-header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--light-color);
}

.child-icon-large {
    width: 80px;
    height: 80px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    margin-right: 20px;
}

.child-title h2 {
    margin: 0 0 5px 0;
    color: var(--text-color);
}

.child-age {
    color: #666;
    font-size: 1.1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: #666;
    font-weight: 500;
}

.info-value {
    color: var(--text-color);
    font-weight: 500;
}

.vaccination-stats {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.vaccination-stats h3 {
    margin-bottom: 20px;
    color: var(--text-color);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.stat-box {
    background: var(--light-color);
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border-top: 4px solid var(--primary-color);
}

.stat-box.completed {
    border-top-color: #27ae60;
}

.stat-box.upcoming {
    border-top-color: #f39c12;
}

.stat-box.overdue {
    border-top-color: #e74c3c;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--text-color);
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.action-btn {
    background: white;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 20px;
    border-radius: 10px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s;
}

.action-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
}

.action-btn i {
    font-size: 1.5rem;
}

.action-btn span {
    font-weight: 500;
    font-size: 1.1rem;
}

.recent-vaccinations {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.recent-vaccinations h3 {
    margin-bottom: 20px;
    color: var(--text-color);
}

.vaccine-table {
    width: 100%;
    border-collapse: collapse;
}

.vaccine-table th {
    background: var(--light-color);
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: var(--text-color);
}

.vaccine-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.vaccine-table tr:last-child td {
    border-bottom: none;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.approved {
    background: #d1ecf1;
    color: #0c5460;
}

.status-badge.completed {
    background: #d4edda;
    color: #155724;
}

.status-badge.rejected {
    background: #f8d7da;
    color: #721c24;
}

.no-data {
    text-align: center;
    color: #666;
    padding: 30px;
    font-style: italic;
}
</style>

</body>
</html>