<?php 
$page_title = "Approve Booking Requests";
include("header.php");
include("../includes/config.php");

$success = "";
$error = "";

// Handle approval/rejection
if (isset($_GET['approve'])) {
    $booking_id = $_GET['approve'];
    $sql = "UPDATE bookings SET status = 1 WHERE booking_id = '$booking_id'";
    if (mysqli_query($conn, $sql)) {
        $success = "Booking approved successfully!";
    } else {
        $error = "Error approving booking: " . mysqli_error($conn);
    }
}

if (isset($_GET['reject'])) {
    $booking_id = $_GET['reject'];
    $sql = "UPDATE bookings SET status = 3 WHERE booking_id = '$booking_id'";
    if (mysqli_query($conn, $sql)) {
        $success = "Booking rejected successfully!";
    } else {
        $error = "Error rejecting booking: " . mysqli_error($conn);
    }
}

// Get pending bookings
$sql = "SELECT 
    b.booking_id,
    c.child_name,
    c.dob as child_dob,
    u.name as parent_name,
    u.email as parent_email,
    h.hospital_name,
    v.vaccine_name,
    b.booking_date,
    b.created_at
    FROM bookings b
    JOIN children c ON b.child_id = c.child_id
    JOIN users u ON c.parent_id = u.user_id
    JOIN hospitals h ON b.hospital_id = h.hospital_id
    JOIN vaccines v ON b.vaccine_id = v.vaccine_id
    WHERE b.status = 0
    ORDER BY b.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<h2>Pending Booking Requests</h2>
<p>Review and approve/reject vaccination appointment requests from parents.</p>

<?php if(mysqli_num_rows($result) > 0): ?>

<div class="bookings-table">
    <table>
        <tr>
            <th>Child Details</th>
            <th>Parent</th>
            <th>Vaccine</th>
            <th>Hospital</th>
            <th>Appointment Date</th>
            <th>Requested On</th>
            <th>Actions</th>
        </tr>
        
        <?php while($row = mysqli_fetch_assoc($result)): 
            // Calculate child age
            $dob = new DateTime($row['child_dob']);
            $today = new DateTime();
            $age = $dob->diff($today)->y;
        ?>
        <tr>
            <td>
                <strong><?php echo $row['child_name']; ?></strong><br>
                <small>Age: <?php echo $age; ?> years</small>
            </td>
            <td>
                <?php echo $row['parent_name']; ?><br>
                <small><?php echo $row['parent_email']; ?></small>
            </td>
            <td><?php echo $row['vaccine_name']; ?></td>
            <td><?php echo $row['hospital_name']; ?></td>
            <td><?php echo date('d M Y', strtotime($row['booking_date'])); ?></td>
            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
            <td>
                <div class="action-buttons">
                    <a href="bookings.php?approve=<?php echo $row['booking_id']; ?>" class="btn-approve">
                        <i class="fas fa-check"></i> Approve
                    </a>
                    <a href="bookings.php?reject=<?php echo $row['booking_id']; ?>" class="btn-reject">
                        <i class="fas fa-times"></i> Reject
                    </a>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php else: ?>

<div class="no-data">
    <i class="fas fa-clipboard-check" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
    <h3>No Pending Requests</h3>
    <p>All booking requests have been processed.</p>
</div>

<?php endif; ?>

<style>
.bookings-table {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-top: 20px;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-approve {
    background: #27ae60;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-approve:hover {
    background: #219653;
}

.btn-reject {
    background: #e74c3c;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-reject:hover {
    background: #c0392b;
}

.no-data {
    text-align: center;
    padding: 50px;
    color: #666;
}
</style>

</body>
</html>