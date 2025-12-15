<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: ../register.php");
        exit();
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters";
        header("Location: ../register.php");
        exit();
    }
    
    // Check if email exists
    $check = "SELECT user_id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Email already registered";
        header("Location: ../register.php");
        exit();
    }
    
    // // Hash password
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert parent
    $sql = "INSERT INTO users (role, name, email, password) 
            VALUES ('parent', '$name', '$email', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        header("Location: ../register.php");
        exit();
    }
}

header("Location: ../register.php");
exit();
?>