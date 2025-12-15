<?php 
$page_title = "System Reports";
include("header.php");
include("../includes/config.php");
?>

<h2>System Reports</h2>
<p>Generate and view system-wide vaccination reports.</p>

<div class="reports-grid">
    <div class="report-card">
        <h3><i class="fas fa-chart-line"></i> Vaccination Coverage Report</h3>
        <p>Overall vaccination completion rates and statistics.</p>
        <a href="generate_report.php?type=coverage" class="btn">Generate Report</a>
    </div>
    
    <div class="report-card">
        <h3><i class="fas fa-hospital"></i> Hospital Performance</h3>
        <p>Vaccination statistics by hospital.</p>
        <a href="generate_report.php?type=hospitals" class="btn">Generate Report</a>
    </div>
    
    <div class="report-card">
        <h3><i class="fas fa-calendar"></i> Date-wise Report</h3>
        <p>Vaccinations completed by date range.</p>
        <a href="generate_report.php?type=datewise" class="btn">Generate Report</a>
    </div>
    
    <div class="report-card">
        <h3><i class="fas fa-syringe"></i> Vaccine-wise Report</h3>
        <p>Statistics for each vaccine type.</p>
        <a href="generate_report.php?type=vaccines" class="btn">Generate Report</a>
    </div>
</div>

<div class="quick-stats" style="background: white; padding: 30px; border-radius: 10px; margin-top: 30px;">
    <h3>Quick Statistics</h3>
    
    <?php
    // Get statistics
    $total_children = mysqli_query($conn, "SELECT COUNT(*) as count FROM children");
    $total_children_count = mysqli_fetch_assoc($total_children)['count'];
    
    $total_vaccinations = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings");
    $total_vaccinations_count = mysqli_fetch_assoc($total_vaccinations)['count'];
    
    $completed_vaccinations = mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status = 2");
    $completed_count = mysqli_fetch_assoc($completed_vaccinations)['count'];
    
    $completion_rate = $total_vaccinations_count > 0 ? round(($completed_count/$total_vaccinations_count)*100) : 0;
    ?>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        <div class="stat-item">
            <div class="stat-number"><?php echo $total_children_count; ?></div>
            <div class="stat-label">Total Children</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number"><?php echo $total_vaccinations_count; ?></div>
            <div class="stat-label">Total Vaccinations</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number"><?php echo $completed_count; ?></div>
            <div class="stat-label">Completed</div>
        </div>
        
        <div class="stat-item">
            <div class="stat-number"><?php echo $completion_rate; ?>%</div>
            <div class="stat-label">Completion Rate</div>
        </div>
    </div>
</div>

<style>
.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.report-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-top: 4px solid var(--primary-color);
}

.report-card h3 {
    margin-bottom: 10px;
    color: var(--text-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.report-card p {
    color: #666;
    margin-bottom: 20px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: var(--light-color);
    border-radius: 10px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}
</style>

</body>
</html>