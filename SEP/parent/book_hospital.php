<?php 
include("header.php");
include("../includes/config.php");

$child_id = $_GET['child_id'] ?? 0;
$vaccine_id = $_GET['vaccine_id'] ?? 0;

// Get hospitals
$hospitals_sql = "SELECT * FROM hospitals WHERE status = 1";
$hospitals_result = mysqli_query($conn, $hospitals_sql);

$error = "";
$success = "";

if ($_POST) {
    $hospital_id = mysqli_real_escape_string($conn, $_POST['hospital_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    
    if (empty($hospital_id) || empty($date) || $child_id == 0 || $vaccine_id == 0) {
        $error = "Please fill all fields";
    } else {
        // Create booking
        $sql = "UPDATE bookings SET hospital_id = '$hospital_id', booking_date = '$date' 
                WHERE child_id = '$child_id' AND vaccine_id = '$vaccine_id'";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Appointment booked! Waiting for admin approval.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="main-content">
    <div class="header">
        <h1>Book Hospital</h1>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <?php if($error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if($child_id == 0 || $vaccine_id == 0): ?>
    
    <div style="text-align: center; padding: 50px; color: #666;">
        <p>Please select a vaccine from the vaccination dates page.</p>
        <a href="vaccination_dates.php" class="btn">Go to Vaccination Dates</a>
    </div>
    
    <?php else: ?>
    
    <div class="form-box">
        <h2>Book Appointment</h2>
        
        <form method="POST">
            <div class="input-group">
                <label>Select Hospital</label>
                <select name="hospital_id" required>
                    <option value="">Choose Hospital</option>
                    <?php while($hospital = mysqli_fetch_assoc($hospitals_result)): ?>
                    <option value="<?php echo $hospital['hospital_id']; ?>">
                        <?php echo $hospital['hospital_name']; ?> - <?php echo $hospital['location']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="input-group">
                <label>Appointment Date</label>
                <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <button type="submit" class="btn">Book Appointment</button>
        </form>
    </div>
    
    <?php endif; ?>
</div>

</body>
</html>