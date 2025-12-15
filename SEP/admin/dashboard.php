<?php 
$page_title = "Admin Dashboard";
include("header.php");
include("../includes/config.php");

// Get statistics
$children = mysqli_query($conn, "SELECT COUNT(*) as count FROM children");
$children_count = mysqli_fetch_assoc($children)['count'];

$parents = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role = 'parent'");
$parents_count = mysqli_fetch_assoc($parents)['count'];

$hospitals = mysqli_query($conn, "SELECT COUNT(*) as count FROM hospitals WHERE status = 1");
$hospitals_count = mysqli_fetch_assoc($hospitals)['count'];

$pending = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status = 0");
$pending_count = mysqli_fetch_assoc($pending)['count'];

$today_vaccinations = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE DATE(booking_date) = CURDATE() AND status = 1");
$today_count = mysqli_fetch_assoc($today_vaccinations)['count'];
?>

<div class="welcome-box">
    <h2>Welcome, System Administrator!</h2>
    <p>Manage the entire vaccination system from here.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-child"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $children_count; ?></h3>
            <p>Total Children</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $parents_count; ?></h3>
            <p>Registered Parents</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-hospital"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $hospitals_count; ?></h3>
            <p>Active Hospitals</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $pending_count; ?></h3>
            <p>Pending Requests</p>
        </div>
    </div>
</div>

<h2 style="margin-bottom: 20px;">Quick Actions</h2>
<div class="action-grid">
    <div class="action-card">
        <i class="fas fa-clipboard-check"></i>
        <h3>Approve Requests</h3>
        <p>Review and approve pending booking requests</p>
        <a href="bookings.php" class="btn">View Requests</a>
    </div>
    
    <div class="action-card">
        <i class="fas fa-hospital-plus"></i>
        <h3>Add Hospital</h3>
        <p>Register new vaccination center</p>
        <a href="add_hospital.php" class="btn">Add New</a>
    </div>
    
    <div class="action-card">
        <i class="fas fa-syringe"></i>
        <h3>Manage Vaccines</h3>
        <p>Update vaccine availability</p>
        <a href="vaccines.php" class="btn">Manage</a>
    </div>
    
    <div class="action-card">
        <i class="fas fa-chart-pie"></i>
        <h3>Generate Reports</h3>
        <p>Create system-wide reports</p>
        <a href="reports.php" class="btn">Generate</a>
    </div>
</div>

<h2 style="margin: 30px 0 20px 0;">Recent Activity</h2>
<div class="recent-table">
    <table>
        <tr>
            <th>Time</th>
            <th>Activity</th>
            <th>Details</th>
        </tr>
        <?php
        $recent_sql = "SELECT 
            c.child_name, 
            u.name as parent_name,
            b.booking_date,
            b.status,
            b.created_at
            FROM bookings b
            JOIN children c ON b.child_id = c.child_id
            JOIN users u ON c.parent_id = u.user_id
            ORDER BY b.created_at DESC 
            LIMIT 5";
        $recent_result = mysqli_query($conn, $recent_sql);
        
        while($row = mysqli_fetch_assoc($recent_result)):
            $status_text = ['Pending', 'Approved', 'Vaccinated', 'Rejected'][$row['status']];
        ?>
        <tr>
            <td><?php echo date('h:i A', strtotime($row['created_at'])); ?></td>
            <td>Booking <?php echo $status_text; ?></td>
            <td><?php echo $row['child_name']; ?> - <?php echo $row['parent_name']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<style>
.welcome-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.action-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    text-align: center;
    transition: all 0.3s;
    border-top: 4px solid var(--primary-color);
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(41, 128, 185, 0.1);
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

.recent-table {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: var(--light-color);
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: var(--text-color);
}

td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

tr:last-child td {
    border-bottom: none;
}
</style>

</body>
</html> 