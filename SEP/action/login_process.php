<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: ../login.php");
        exit();
    }
    
    // Check user - FIXED JOIN CONDITION
    $sql = "SELECT u.user_id, u.name, u.email, u.password, u.role, h.status as hospital_status 
            FROM users u 
            LEFT JOIN hospitals h ON u.user_id = h.user_id  -- CHANGED THIS LINE
            WHERE u.email = '$email' AND u.password = '$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Check if hospital is approved (status = 1)
        // Note: In your table, status=1 means active, 0 means inactive
        if ($user['role'] == 'hospital' && $user['hospital_status'] != 1) {
            $_SESSION['error'] = "Hospital account is inactive. Please contact admin.";
            header("Location: ../login.php");
            exit();
        }
        
        // Login successful
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        
        // Debug: Check what we're getting
        // echo "<pre>"; print_r($user); echo "</pre>"; exit();
        
        // Redirect based on role
        if ($user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } elseif ($user['role'] == 'hospital') {
            header("Location: ../hospital/dashboard.php");
        } else {
            header("Location: ../parent/dashboard.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: ../login.php");
        exit();
    }
}

header("Location: ../login.php");
exit();
?>