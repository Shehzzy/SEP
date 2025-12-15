<?php 
include("header.php");
include("../includes/config.php");

$parent_id = $_SESSION['user_id'];
$child_id = $_GET['child_id'] ?? 0;

// Get all children for dropdown
$children_sql = "SELECT * FROM children WHERE parent_id = '$parent_id'";
$children_result = mysqli_query($conn, $children_sql);

// Get reports for selected child
$reports = [];
if ($child_id > 0) {
    $report_sql = "SELECT v.vaccine_name, b.booking_date, b.status, r.vaccination_date, r.remarks 
                   FROM bookings b
                   JOIN vaccines v ON b.vaccine_id = v.vaccine_id
                   LEFT JOIN vaccination_reports r ON b.booking_id = r.booking_id
                   WHERE b.child_id = '$child_id'
                   ORDER BY b.booking_date DESC";
    $report_result = mysqli_query($conn, $report_sql);
    while($row = mysqli_fetch_assoc($report_result)) {
        $reports[] = $row;
    }
}
?>

<div class="main-content">
    <div class="header">
        <h1>Vaccination Reports</h1>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div style="margin-bottom: 20px;">
        <label>Select Child:</label>
        <select onchange="window.location='reports.php?child_id=' + this.value">
            <option value="">-- Choose Child --</option>
            <?php while($child = mysqli_fetch_assoc($children_result)): ?>
            <option value="<?php echo $child['child_id']; ?>" <?php echo $child_id == $child['child_id'] ? 'selected' : ''; ?>>
                <?php echo $child['child_name']; ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <?php if($child_id > 0): ?>
    
    <?php if(count($reports) > 0): ?>
    
    <table class="data-table">
        <tr>
            <th>Vaccine</th>
            <th>Scheduled Date</th>
            <th>Vaccinated Date</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
        
        <?php foreach($reports as $report): 
            $status = $report['status'];
            $status_text = ['Pending', 'Approved', 'Vaccinated', 'Rejected'][$status];
            $status_color = ['#ffc107', '#17a2b8', '#28a745', '#dc3545'][$status];
        ?>
        <tr>
            <td><?php echo $report['vaccine_name']; ?></td>
            <td><?php echo date('d M Y', strtotime($report['booking_date'])); ?></td>
            <td>
                <?php if($report['vaccination_date']): ?>
                    <?php echo date('d M Y', strtotime($report['vaccination_date'])); ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><span style="background: <?php echo $status_color; ?>; color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.8rem;"><?php echo $status_text; ?></span></td>
            <td><?php echo $report['remarks'] ?: '-'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
        <h4>Summary</h4>
        <?php
        $total = count($reports);
        $completed = 0;
        $pending = 0;
        foreach($reports as $report) {
            if($report['status'] == 2) $completed++;
            if($report['status'] == 0) $pending++;
        }
        ?>
        <p>Total Vaccines: <?php echo $total; ?></p>
        <p>Completed: <?php echo $completed; ?></p>
        <p>Pending: <?php echo $pending; ?></p>
        <p>Completion Rate: <?php echo $total > 0 ? round(($completed/$total)*100) : 0; ?>%</p>
    </div>
    
    <?php else: ?>
    
    <div style="text-align: center; padding: 30px; color: #666;">
        <p>No vaccination records found for this child.</p>
    </div>
    
    <?php endif; ?>
    
    <?php else: ?>
    
    <div style="text-align: center; padding: 50px; color: #666;">
        <i class="fas fa-file-medical" style="font-size: 50px; color: #ddd;"></i>
        <p>Please select a child to view vaccination reports</p>
    </div>
    
    <?php endif; ?>
</div>

<style>
.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.data-table th {
    background: var(--light-color);
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.data-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.data-table tr:last-child td {
    border-bottom: none;
}
</style>

</body>
</html>