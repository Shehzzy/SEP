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
    
    // Check user
    $sql = "SELECT user_id, name, email, password, role FROM users WHERE email = '$email' and password='$password'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
            // Login successful
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
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
        $_SESSION['error'] = "Email not found";
        header("Location: ../login.php");
        exit();
    }
}

header("Location: ../login.php");
exit();
?>