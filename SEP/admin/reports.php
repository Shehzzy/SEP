<?php 
$page_title = "Reports";
include("header.php");
include("../includes/config.php");
?>

<h2>System Reports</h2>
<p>Generate vaccination reports for your academic project</p>

<div class="simple-reports">
    <!-- Report 1: Basic Overview -->
    <div class="report-box">
        <h3><i class="fas fa-chart-bar"></i> Basic Overview</h3>
        <p>Total children registered and vaccination counts</p>
        <?php
        $total_kids = mysqli_query($conn, "SELECT COUNT(*) as count FROM children");
        $kids = mysqli_fetch_assoc($total_kids)['count'];
        
        $total_vacc = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status=2");
        $vacc = mysqli_fetch_assoc($total_vacc)['count'];
        ?>
        <div class="mini-stats">
            <div class="stat">
                <span class="number"><?php echo $kids; ?></span>
                <span class="label">Children</span>
            </div>
            <div class="stat">
                <span class="number"><?php echo $vacc; ?></span>
                <span class="label">Vaccinations</span>
            </div>
        </div>
        <a href="generate_report.php?type=overview" class="btn">Generate PDF</a>
    </div>
    
    <!-- Report 2: Hospital Performance -->
    <div class="report-box">
        <h3><i class="fas fa-hospital"></i> Hospital Report</h3>
        <p>See how hospitals are performing</p>
        <?php
        $hospitals = mysqli_query($conn, "SELECT COUNT(*) as count FROM hospitals");
        $hosp_count = mysqli_fetch_assoc($hospitals)['count'];
        
        $top_hospital = mysqli_query($conn, "
            SELECT h.hospital_name, COUNT(b.booking_id) as vaccines 
            FROM hospitals h 
            LEFT JOIN bookings b ON h.hospital_id = b.hospital_id AND b.status=2 
            GROUP BY h.hospital_id 
            ORDER BY vaccines DESC 
            LIMIT 1
        ");
        if($top = mysqli_fetch_assoc($top_hospital)) {
            $top_name = $top['hospital_name'];
            $top_count = $top['vaccines'];
        }
        ?>
        <div class="mini-stats">
            <div class="stat">
                <span class="number"><?php echo $hosp_count; ?></span>
                <span class="label">Hospitals</span>
            </div>
            <div class="stat">
                <span class="number"><?php echo $top_count ?? 0; ?></span>
                <span class="label">Top: <?php echo substr($top_name ?? 'N/A', 0, 10); ?></span>
            </div>
        </div>
        <a href="generate_report.php?type=hospitals" class="btn">Generate PDF</a>
    </div>
    
    <!-- Report 3: Parents Report -->
    <div class="report-box">
        <h3><i class="fas fa-users"></i> Parents Report</h3>
        <p>Parent information and statistics</p>
        <?php
        $parents = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='parent'");
        $parent_count = mysqli_fetch_assoc($parents)['count'];
        
        $active_parents = mysqli_query($conn, "
            SELECT COUNT(DISTINCT u.user_id) as count 
            FROM users u 
            JOIN children c ON u.user_id = c.parent_id 
            JOIN bookings b ON c.child_id = b.child_id 
            WHERE u.role='parent'
        ");
        $active_count = mysqli_fetch_assoc($active_parents)['count'];
        ?>
        <div class="mini-stats">
            <div class="stat">
                <span class="number"><?php echo $parent_count; ?></span>
                <span class="label">Total Parents</span>
            </div>
            <div class="stat">
                <span class="number"><?php echo $active_count; ?></span>
                <span class="label">Active Parents</span>
            </div>
        </div>
        <a href="generate_report.php?type=parents" class="btn">Generate PDF</a>
    </div>
</div>

<!-- Quick Stats -->
<div class="simple-stats">
    <h3>Quick Numbers</h3>
    <div class="stats-row">
        <?php
        // Get all basic stats
        $stats = [
            'children' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM children"))['count'],
            'vaccinations' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status=2"))['count'],
            'hospitals' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM hospitals"))['count'],
            'parents' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='parent'"))['count'],
            'pending' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status=1"))['count'],
            'today' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at)=CURDATE()"))['count']
        ];
        ?>
        <div class="stat-item">
            <span class="icon">👶</span>
            <span class="value"><?php echo $stats['children']; ?></span>
            <span class="label">Children</span>
        </div>
        <div class="stat-item">
            <span class="icon">💉</span>
            <span class="value"><?php echo $stats['vaccinations']; ?></span>
            <span class="label">Vaccinations</span>
        </div>
        <div class="stat-item">
            <span class="icon">🏥</span>
            <span class="value"><?php echo $stats['hospitals']; ?></span>
            <span class="label">Hospitals</span>
        </div>
        <div class="stat-item">
            <span class="icon">👪</span>
            <span class="value"><?php echo $stats['parents']; ?></span>
            <span class="label">Parents</span>
        </div>
    </div>
</div>

<style>
.simple-reports {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.report-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #4CAF50;
}

.report-box h3 {
    color: #333;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.report-box p {
    color: #666;
    margin-bottom: 15px;
}

.mini-stats {
    display: flex;
    gap: 15px;
    margin: 15px 0;
}

.mini-stats .stat {
    text-align: center;
    flex: 1;
    background: #f5f5f5;
    padding: 10px;
    border-radius: 5px;
}

.mini-stats .number {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #4CAF50;
}

.mini-stats .label {
    font-size: 12px;
    color: #666;
}

.btn {
    display: inline-block;
    background: #4CAF50;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}

.btn:hover {
    background: #45a049;
}

.simple-stats {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.stat-item {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    border: 1px solid #eee;
}

.stat-item .icon {
    font-size: 24px;
    display: block;
    margin-bottom: 5px;
}

.stat-item .value {
    display: block;
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.stat-item .label {
    font-size: 14px;
    color: #666;
}
</style>
