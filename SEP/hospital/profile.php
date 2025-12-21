<?php
require_once 'header.php';

// Get hospital details
$query = "SELECT h.*, u.email, u.name as admin_name
          FROM hospitals h
          JOIN users u ON h.user_id = u.user_id
          WHERE h.hospital_id = '$hospital_id'";
$result = mysqli_query($conn, $query);
$hospital = mysqli_fetch_assoc($result);

// Check if columns exist before accessing them
$contact_phone = isset($hospital['contact_phone']) ? $hospital['contact_phone'] : '';
$contact_email = isset($hospital['contact_email']) ? $hospital['contact_email'] : '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $hospital_name = mysqli_real_escape_string($conn, $_POST['hospital_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    
    // Simple update query - only update fields that exist in your table
    $update_query = "UPDATE hospitals 
                    SET hospital_name = '$hospital_name',
                        address = '$address',
                        location = '$location'
                    WHERE hospital_id = '$hospital_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $message = "Profile updated successfully!";
        // Refresh hospital data
        $result = mysqli_query($conn, $query);
        $hospital = mysqli_fetch_assoc($result);
        $_SESSION['hospital_name'] = $hospital['hospital_name'];
    } else {
        $error = "Error updating profile: " . mysqli_error($conn);
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password hash
    $user_id = $_SESSION['user_id'];
    $pass_query = "SELECT password FROM users WHERE user_id = '$user_id'";
    $pass_result = mysqli_query($conn, $pass_query);
    $user = mysqli_fetch_assoc($pass_result);
    
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pass = "UPDATE users SET password = '$hashed_password' WHERE user_id = '$user_id'";
            
            if (mysqli_query($conn, $update_pass)) {
                $pass_message = "Password changed successfully!";
            } else {
                $pass_error = "Error changing password";
            }
        } else {
            $pass_error = "New passwords don't match!";
        }
    } else {
        $pass_error = "Current password is incorrect!";
    }
}
?>

<div class="welcome-box">
    <h2>Hospital Profile</h2>
    <p>Manage your hospital information and settings</p>
</div>

<?php if(isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>
<?php if(isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Hospital Information -->
<div style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom: 20px; color: var(--primary-color);">
        <i class="fas fa-hospital-alt"></i> Hospital Information
    </h3>
    
    <form method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Hospital Name *</label>
                <input type="text" name="hospital_name" value="<?php echo htmlspecialchars($hospital['hospital_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Location/City *</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($hospital['location']); ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Full Address</label>
            <textarea name="address" rows="3"><?php echo htmlspecialchars($hospital['address']); ?></textarea>
        </div>
        
        <!-- Only show contact fields if they exist in database -->
        <?php if(isset($hospital['contact_phone']) || isset($hospital['contact_email'])): ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <?php if(isset($hospital['contact_phone'])): ?>
            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="contact_phone" value="<?php echo htmlspecialchars($contact_phone); ?>">
            </div>
            <?php endif; ?>
            
            <?php if(isset($hospital['contact_email'])): ?>
            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" name="contact_email" value="<?php echo htmlspecialchars($contact_email); ?>">
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label>Administrator Name</label>
            <input type="text" value="<?php echo htmlspecialchars($hospital['admin_name']); ?>" readonly style="background: #f8f9fa;">
        </div>
        
        <div class="form-group">
            <label>Login Email</label>
            <input type="email" value="<?php echo htmlspecialchars($hospital['email']); ?>" readonly style="background: #f8f9fa;">
        </div>
        
        <div class="form-group">
            <label>Account Status</label>
            <input type="text" value="<?php echo $hospital['status'] == 1 ? 'Active' : 'Inactive'; ?>" 
                   readonly style="background: #f8f9fa; color: <?php echo $hospital['status'] == 1 ? 'green' : 'red'; ?>;">
        </div>
        
        <button type="submit" name="update_profile" class="btn">Update Hospital Information</button>
    </form>
</div>


<!-- Hospital Statistics -->
<div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom: 20px; color: var(--primary-color);">
        <i class="fas fa-chart-line"></i> Hospital Statistics
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <?php
        // Get statistics - simplified queries
        $total_bookings_query = "SELECT COUNT(*) as total FROM bookings WHERE hospital_id = '$hospital_id'";
        $total_result = mysqli_query($conn, $total_bookings_query);
        $total_bookings = mysqli_fetch_assoc($total_result)['total'];
        
        $completed_query = "SELECT COUNT(*) as completed FROM bookings WHERE hospital_id = '$hospital_id' AND status = 2";
        $completed_result = mysqli_query($conn, $completed_query);
        $completed = mysqli_fetch_assoc($completed_result)['completed'];
        
        $children_query = "SELECT COUNT(DISTINCT c.child_id) as children 
                          FROM children c 
                          JOIN bookings b ON c.child_id = b.child_id 
                          WHERE b.hospital_id = '$hospital_id'";
        $children_result = mysqli_query($conn, $children_query);
        $unique_children = mysqli_fetch_assoc($children_result)['children'];
        
        $vaccines_query = "SELECT COUNT(DISTINCT vaccine_id) as vaccine_types 
                          FROM bookings WHERE hospital_id = '$hospital_id'";
        $vaccines_result = mysqli_query($conn, $vaccines_query);
        $vaccine_types = mysqli_fetch_assoc($vaccines_result)['vaccine_types'];
        ?>
        
        <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: var(--primary-color);"><?php echo $total_bookings; ?></h4>
            <p>Total Bookings</p>
        </div>
        
        <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: var(--primary-color);"><?php echo $completed; ?></h4>
            <p>Vaccinations Completed</p>
        </div>
        
        <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: var(--primary-color);"><?php echo $unique_children; ?></h4>
            <p>Children Served</p>
        </div>
        
        <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: var(--primary-color);"><?php echo $vaccine_types; ?></h4>
            <p>Vaccine Types Given</p>
        </div>
    </div>
</div>