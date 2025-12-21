<?php
// Simple check without requiring header
require_once '../includes/config.php';
session_start();

echo "<h2>Vaccination System Database Check</h2>";
echo "<pre>";

// Check connection
echo "Database Connection: ";
echo $conn ? "✓ Connected" : "✗ Failed";
echo "\n";

// Check users table
echo "\n=== USERS TABLE ===\n";
$users_query = "SELECT COUNT(*) as count FROM users";
$users_result = mysqli_query($conn, $users_query);
$users_count = mysqli_fetch_assoc($users_result)['count'];
echo "Total Users: " . $users_count . "\n";

// Show hospital users
echo "\nHospital Users:\n";
$hosp_users = "SELECT user_id, name, email FROM users WHERE role = 'hospital'";
$hosp_result = mysqli_query($conn, $hosp_users);
while ($row = mysqli_fetch_assoc($hosp_result)) {
    echo "ID: " . $row['user_id'] . " | Name: " . $row['name'] . " | Email: " . $row['email'] . "\n";
}

// Check hospitals table
echo "\n=== HOSPITALS TABLE ===\n";
$hospitals_query = "SELECT COUNT(*) as count FROM hospitals";
$hospitals_result = mysqli_query($conn, $hospitals_query);
$hospitals_count = mysqli_fetch_assoc($hospitals_result)['count'];
echo "Total Hospitals: " . $hospitals_count . "\n";

// Show all hospitals
echo "\nAll Hospitals:\n";
$all_hospitals = "SELECT hospital_id, user_id, hospital_name, location FROM hospitals";
$all_result = mysqli_query($conn, $all_hospitals);
while ($row = mysqli_fetch_assoc($all_result)) {
    echo "Hospital ID: " . $row['hospital_id'] . " | User ID: " . $row['user_id'] . " | Name: " . $row['hospital_name'] . " | Location: " . $row['location'] . "\n";
}

// Check current session
echo "\n=== CURRENT SESSION ===\n";
if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION)) {
    echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "\n";
    echo "Role: " . ($_SESSION['role'] ?? 'Not set') . "\n";
    echo "Hospital ID: " . ($_SESSION['hospital_id'] ?? 'Not set') . "\n";
    echo "Hospital Name: " . ($_SESSION['hospital_name'] ?? 'Not set') . "\n";
    
    // If we have user_id, find associated hospital
    if (isset($_SESSION['user_id'])) {
        $uid = $_SESSION['user_id'];
        $find_hospital = "SELECT hospital_id FROM hospitals WHERE user_id = '$uid'";
        $find_result = mysqli_query($conn, $find_hospital);
        if ($find_result && mysqli_num_rows($find_result) > 0) {
            $found = mysqli_fetch_assoc($find_result);
            echo "\nHospital found for user $uid: ID = " . $found['hospital_id'];
        } else {
            echo "\nNO HOSPITAL FOUND FOR USER $uid!";
        }
    }
} else {
    echo "No active session\n";
}

echo "</pre>";
echo "<hr>";
echo "<h3>Quick Links:</h3>";
echo "<a href='dashboard.php'>Go to Dashboard</a> | ";
echo "<a href='../logout.php'>Logout</a> | ";
echo "<a href='profile.php'>Profile Page</a>";
?>