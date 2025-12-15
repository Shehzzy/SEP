
<?php 
$page_title = "Add New Hospital";
include("header.php");
include("../includes/config.php");

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hospital_name = mysqli_real_escape_string($conn, $_POST['hospital_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $contact_name = mysqli_real_escape_string($conn, $_POST['contact_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($hospital_name) || empty($address) || empty($location) || empty($contact_name) || empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Check if email exists
        $check_sql = "SELECT user_id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered!";
        } else {
            // Start transaction
            mysqli_begin_transaction($conn);
            
            try {
                // Create user account
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $user_sql = "INSERT INTO users (role, name, email, password) 
                            VALUES ('hospital', '$contact_name', '$email', '$hashed_password')";
                
                if (!mysqli_query($conn, $user_sql)) {
                    throw new Exception("Error creating user: " . mysqli_error($conn));
                }
                
                $user_id = mysqli_insert_id($conn);
                
                // Create hospital
                $hospital_sql = "INSERT INTO hospitals (user_id, hospital_name, address, location, status) 
                               VALUES ('$user_id', '$hospital_name', '$address', '$location', 1)";
                
                if (!mysqli_query($conn, $hospital_sql)) {
                    throw new Exception("Error creating hospital: " . mysqli_error($conn));
                }
                
                mysqli_commit($conn);
                $success = "Hospital added successfully! Login credentials sent to $email";
                
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }
        }
    }
}
?>

<?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="form-container">
    <h2>Add New Hospital</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Hospital Name *</label>
            <input type="text" name="hospital_name" required>
        </div>
        
        <div class="form-group">
            <label>Address *</label>
            <textarea name="address" rows="3" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Location/City *</label>
            <input type="text" name="location" required>
        </div>
        
        <div class="form-group">
            <label>Contact Person Name *</label>
            <input type="text" name="contact_name" required>
        </div>
        
        <div class="form-group">
            <label>Contact Email *</label>
            <input type="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" required>
            <small>At least 6 characters</small>
        </div>
        
        <button type="submit" class="btn">Add Hospital</button>
        <a href="hospitals.php" class="btn" style="background: #95a5a6;">Cancel</a>
    </form>
</div>

<style>
.form-container {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.form-group small {
    color: #666;
    font-size: 0.8rem;
}
</style>

</body>
</html>