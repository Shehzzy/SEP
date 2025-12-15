<?php 
$page_title = "Manage Hospitals";
include("header.php");
include("../includes/config.php");

$success = "";
$error = "";

// Handle hospital status toggle
if (isset($_GET['toggle'])) {
    $hospital_id = $_GET['toggle'];
    
    // Get current status
    $check_sql = "SELECT status FROM hospitals WHERE hospital_id = '$hospital_id'";
    $check_result = mysqli_query($conn, $check_sql);
    $hospital = mysqli_fetch_assoc($check_result);
    
    $new_status = $hospital['status'] == 1 ? 0 : 1;
    
    $sql = "UPDATE hospitals SET status = '$new_status' WHERE hospital_id = '$hospital_id'";
    if (mysqli_query($conn, $sql)) {
        $action = $new_status == 1 ? "activated" : "deactivated";
        $success = "Hospital $action successfully!";
    } else {
        $error = "Error updating hospital: " . mysqli_error($conn);
    }
}

// Get all hospitals
$sql = "SELECT 
    h.*,
    u.name as contact_person,
    u.email
    FROM hospitals h
    JOIN users u ON h.user_id = u.user_id
    ORDER BY h.hospital_name ASC";
$result = mysqli_query($conn, $sql);
?>

<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Hospitals</h2>
    <a href="add_hospital.php" class="btn">+ Add New Hospital</a>
</div>

<?php if(mysqli_num_rows($result) > 0): ?>

<div class="hospitals-table">
    <table>
        <tr>
            <th>Hospital Name</th>
            <th>Contact Person</th>
            <th>Location</th>
            <th>Address</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <strong><?php echo $row['hospital_name']; ?></strong><br>
                <small>Email: <?php echo $row['email']; ?></small>
            </td>
            <td><?php echo $row['contact_person']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td>
                <?php if($row['status'] == 1): ?>
                    <span class="status-active">Active</span>
                <?php else: ?>
                    <span class="status-inactive">Inactive</span>
                <?php endif; ?>
            </td>
            <td>
                <div class="action-buttons">
                    <a href="hospitals.php?toggle=<?php echo $row['hospital_id']; ?>" class="btn-status">
                        <?php if($row['status'] == 1): ?>
                            <i class="fas fa-ban"></i> Deactivate
                        <?php else: ?>
                            <i class="fas fa-check"></i> Activate
                        <?php endif; ?>
                    </a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php else: ?>

<div class="no-data">
    <i class="fas fa-hospital" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
    <h3>No Hospitals Found</h3>
    <p>Add your first hospital to get started.</p>
    <a href="add_hospital.php" class="btn">Add Hospital</a>
</div>

<?php endif; ?>

<style>
.hospitals-table {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.status-active {
    background: #d4edda;
    color: #155724;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-status {
    background: #f39c12;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-status:hover {
    background: #e67e22;
}
</style>

</body>
</html>