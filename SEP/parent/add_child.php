<?php 
include("header.php");
include("../includes/config.php");

$error = "";
$success = "";

if ($_POST) {
    $child_name = mysqli_real_escape_string($conn, $_POST['child_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $parent_id = $_SESSION['user_id'];
    
    if (empty($child_name) || empty($dob) || empty($gender)) {
        $error = "Please fill all required fields";
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // 1. Add child
            $sql = "INSERT INTO children (parent_id, child_name, dob, gender, weight, blood_group) 
                    VALUES ('$parent_id', '$child_name', '$dob', '$gender', '$weight', '$blood_group')";
            
            if (!mysqli_query($conn, $sql)) {
                throw new Exception("Error adding child: " . mysqli_error($conn));
            }
            
            $child_id = mysqli_insert_id($conn);
            
            // 2. Get all vaccines from database
            $vaccines_sql = "SELECT vaccine_id FROM vaccines ORDER BY vaccine_id";
            $vaccines_result = mysqli_query($conn, $vaccines_sql);
            
            if (mysqli_num_rows($vaccines_result) == 0) {
                throw new Exception("No vaccines found in database!");
            }
            
            // 3. Create schedule for each vaccine
            while($vaccine = mysqli_fetch_assoc($vaccines_result)) {
                $vaccine_id = $vaccine['vaccine_id'];
                
                // Simple schedule: 30 days between each vaccine starting from DOB
                $days = ($vaccine_id - 1) * 30; // Vaccine 1 at 0 days, Vaccine 2 at 30 days, etc.
                $vaccination_date = date('Y-m-d', strtotime($dob . " + $days days"));
                
                $schedule_sql = "INSERT INTO bookings (child_id, vaccine_id, booking_date, status) 
                                 VALUES ('$child_id', '$vaccine_id', '$vaccination_date', 0)";
                
                if (!mysqli_query($conn, $schedule_sql)) {
                    throw new Exception("Error creating schedule: " . mysqli_error($conn));
                }
            }
            
            // Commit transaction
            mysqli_commit($conn);
            $success = "Child added successfully! Vaccination schedule created.";
            
        } catch (Exception $e) {
            // Rollback on error
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
}
?>
<div class="main-content">
    <div class="header">
        <h1>Add Child</h1>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <?php if($error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="form-box">
        <h2>Add New Child</h2>
        
        <form method="POST">
            <div class="input-group">
                <label>Child Name *</label>
                <input type="text" name="child_name" required>
            </div>
            
            <div class="input-group">
                <label>Date of Birth *</label>
                <input type="date" name="dob" required>
            </div>
            
            <div class="input-group">
                <label>Gender *</label>
                <select name="gender" required>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            
            <!-- NEW: Weight field -->
            <div class="input-group">
                <label>Weight (kg)</label>
                <input type="number" name="weight" step="0.1" min="0" placeholder="e.g., 3.5">
            </div>
            
            <!-- NEW: Blood group field -->
            <div class="input-group">
                <label>Blood Group</label>
                <select name="blood_group">
                    <option value="">Select Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Add Child</button>
        </form>
    </div>
</div>

<style>
.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.form-box {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    max-width: 500px;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.input-group input,
.input-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
</style>

</body>
</html>