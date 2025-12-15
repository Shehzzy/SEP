<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $hospital_name = mysqli_real_escape_string($conn, $_POST['hospital_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    
    if (empty($name) || empty($email) || empty($password) || empty($hospital_name) || empty($address)) {
        $_SESSION['error'] = "All required fields must be filled";
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
    
    // Insert user first
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_sql = "INSERT INTO users (role, name, email, password) 
                 VALUES ('hospital', '$name', '$email', '$password')";
    
    if (!mysqli_query($conn, $user_sql)) {
        $_SESSION['error'] = "User registration failed: " . mysqli_error($conn);
        header("Location: register.php");
        exit();
    }
    
    $user_id = mysqli_insert_id($conn);
    
    // Insert hospital
    $hospital_sql = "INSERT INTO hospitals (user_id, hospital_name, address, location, status) 
                     VALUES ('$user_id', '$hospital_name', '$address', '$location', 1)";
    
    if (mysqli_query($conn, $hospital_sql)) {
        $_SESSION['success'] = "Hospital registration successful! Please login.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Hospital registration failed: " . mysqli_error($conn);
        header("Location: ../register.php");
        exit();
    }
}

header("Location: ../register.php");
exit();
?>