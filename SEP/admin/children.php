<?php 
$page_title = "All Children";
include("header.php");
include("../includes/config.php");

// Get all children with parent info
$sql = "SELECT 
    c.*,
    u.name as parent_name,
    u.email as parent_email,
    (SELECT COUNT(*) FROM bookings WHERE child_id = c.child_id) as total_vaccines,
    (SELECT COUNT(*) FROM bookings WHERE child_id = c.child_id AND status = 2) as completed_vaccines
    FROM children c
    JOIN users u ON c.parent_id = u.user_id
    ORDER BY c.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<h2>All Children in System</h2>
<p>View details of all registered children and their vaccination progress.</p>

<?php if(mysqli_num_rows($result) > 0): ?>

<div class="children-table">
    <table>
        <tr>
            <th>Child Name</th>
            <th>Age</th>
            <th>Parent</th>
            <!-- <th>Vaccination Progress</th> -->
            <th>Registered On</th>
        </tr>
        
        <?php while($row = mysqli_fetch_assoc($result)): 
            // Calculate age
            $dob = new DateTime($row['dob']);
            $today = new DateTime();
            $age = $dob->diff($today);
            $age_display = $age->y > 0 ? $age->y . ' years' : ($age->m > 0 ? $age->m . ' months' : $age->d . ' days');
            
            // Calculate progress
            $total = $row['total_vaccines'];
            $completed = $row['completed_vaccines'];
            $progress = $total > 0 ? round(($completed/$total)*100) : 0;
        ?>
        <tr>
            <td>
                <strong><?php echo $row['child_name']; ?></strong><br>
                <small>DOB: <?php echo date('d M Y', strtotime($row['dob'])); ?></small>
            </td>
            <td><?php echo $age_display; ?></td>
            <td>
                <?php echo $row['parent_name']; ?><br>
                <small><?php echo $row['parent_email']; ?></small>
            </td>
            <!-- <td>
                <div class="progress-container">
                    <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                    <span class="progress-text"><?php echo $completed; ?>/<?php echo $total; ?> (<?php echo $progress; ?>%)</span>
                </div>
            </td> -->
            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php else: ?>

<div class="no-data">
    <i class="fas fa-child" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
    <h3>No Children Registered</h3>
    <p>No children have been registered in the system yet.</p>
</div>

<?php endif; ?>

<style>
.children-table {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-top: 20px;
    width: 100%;
}


table {
    width: 100%;
  border-collapse: collapse;
  border-radius: 8px;
  overflow: hidden;
  border-style: hidden;
  box-shadow: rgba(0, 0, 0, 1) 0px 0px 0px 1px inset;
}

th, td {
  padding: 1em;
  border: 1px solid black; 
}

.progress-container {
    width: 100%;
    height: 20px;
    background:rgb(67, 99, 107);
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    background: #27ae60;
    transition: width 0.3s;
}

.progress-text {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-small {
    background: var(--primary-color);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-small:hover {
    background: var(--secondary-color);
}
</style>

</body>
</html>