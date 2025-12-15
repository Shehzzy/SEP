<?php 
include("header.php");
include("../includes/config.php");
$parent_id = $_SESSION['user_id'];
$child_id = $_GET['child_id'] ?? 0;

// Get all children for dropdown
$children_sql = "SELECT * FROM children WHERE parent_id = '$parent_id'";
$children_result = mysqli_query($conn, $children_sql);
?>

<div class="main-content">
    <div class="header">
        <h1>Vaccination Dates</h1>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div style="margin-bottom: 20px;">
        <label>Select Child:</label>
        <select onchange="window.location='vaccination_dates.php?child_id=' + this.value">
            <option value="">-- Choose Child --</option>
            <?php while($child = mysqli_fetch_assoc($children_result)): ?>
            <option value="<?php echo $child['child_id']; ?>" <?php echo $child_id == $child['child_id'] ? 'selected' : ''; ?>>
                <?php echo $child['child_name']; ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>

    <?php if($child_id > 0): 
        // Get vaccination schedule for selected child
        $schedule_sql = "SELECT b.*, v.vaccine_name 
                        FROM bookings b 
                        JOIN vaccines v ON b.vaccine_id = v.vaccine_id 
                        WHERE b.child_id = '$child_id' 
                        ORDER BY b.booking_date";
        $schedule_result = mysqli_query($conn, $schedule_sql);
    ?>
    
    <?php if(mysqli_num_rows($schedule_result) > 0): ?>
    
    <table class="data-table">
        <tr>
            <th>Vaccine</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        
        <?php while($row = mysqli_fetch_assoc($schedule_result)): 
            $status = $row['status'];
            $status_text = ['Pending', 'Approved', 'Vaccinated', 'Rejected'][$status];
            $status_color = ['#ffc107', '#17a2b8', '#28a745', '#dc3545'][$status];
        ?>
        <tr>
            <td><?php echo $row['vaccine_name']; ?></td>
            <td><?php echo date('d M Y', strtotime($row['booking_date'])); ?></td>
            <td><span style="background: <?php echo $status_color; ?>; color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.8rem;"><?php echo $status_text; ?></span></td>
            <td>
                <?php if($status == 0): ?>
                    <a href="book_hospital.php?child_id=<?php echo $child_id; ?>&vaccine_id=<?php echo $row['vaccine_id']; ?>" class="btn-small">Book Now</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <?php else: ?>
    
    <div style="text-align: center; padding: 30px; color: #666;">
        <p>No vaccination schedule found for this child.</p>
    </div>
    
    <?php endif; ?>
    
    <?php else: ?>
    
    <div style="text-align: center; padding: 50px; color: #666;">
        <i class="fas fa-calendar-alt" style="font-size: 50px; color: #ddd;"></i>
        <p>Please select a child to view vaccination dates</p>
    </div>
    
    <?php endif; ?>
</div>

</body>
</html>