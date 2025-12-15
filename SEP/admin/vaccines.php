<?php 
$page_title = "Manage Vaccines";
include("header.php");
include("../includes/config.php");

$success = "";
$error = "";

// Handle vaccine status toggle
if (isset($_GET['toggle'])) {
    $vaccine_id = $_GET['toggle'];
    
    // Get current status
    $check_sql = "SELECT availability FROM vaccines WHERE vaccine_id = '$vaccine_id'";
    $check_result = mysqli_query($conn, $check_sql);
    $vaccine = mysqli_fetch_assoc($check_result);
    
    $new_status = $vaccine['availability'] == 1 ? 0 : 1;
    
    $sql = "UPDATE vaccines SET availability = '$new_status' WHERE vaccine_id = '$vaccine_id'";
    if (mysqli_query($conn, $sql)) {
        $action = $new_status == 1 ? "available" : "unavailable";
        $success = "Vaccine marked as $action!";
    } else {
        $error = "Error updating vaccine: " . mysqli_error($conn);
    }
}

// Handle add new vaccine
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vaccine'])) {
    $vaccine_name = mysqli_real_escape_string($conn, $_POST['vaccine_name']);
    
    if (empty($vaccine_name)) {
        $error = "Vaccine name is required!";
    } else {
        $sql = "INSERT INTO vaccines (vaccine_name) VALUES ('$vaccine_name')";
        if (mysqli_query($conn, $sql)) {
            $success = "Vaccine added successfully!";
        } else {
            $error = "Error adding vaccine: " . mysqli_error($conn);
        }
    }
}

// Get all vaccines
$sql = "SELECT * FROM vaccines ORDER BY vaccine_id";
$result = mysqli_query($conn, $sql);
?>

<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<h2>Manage Vaccines</h2>
<p>Update vaccine availability and add new vaccines to the system.</p>

<div class="add-vaccine-form" style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
    <h3>Add New Vaccine</h3>
    <form method="POST" style="display: flex; gap: 10px;">
        <input type="text" name="vaccine_name" placeholder="Enter vaccine name" style="flex: 1; padding: 10px;">
        <button type="submit" name="add_vaccine" class="btn">Add Vaccine</button>
    </form>
</div>

<?php if(mysqli_num_rows($result) > 0): ?>

<div class="vaccines-table">
    <table>
        <tr>
            <th>Vaccine ID</th>
            <th>Vaccine Name</th>
            <th>Availability</th>
            <th>Action</th>
        </tr>
        
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>#<?php echo $row['vaccine_id']; ?></td>
            <td><?php echo $row['vaccine_name']; ?></td>
            <td>
                <?php if($row['availability'] == 1): ?>
                    <span class="status-available">Available</span>
                <?php else: ?>
                    <span class="status-unavailable">Unavailable</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="vaccines.php?toggle=<?php echo $row['vaccine_id']; ?>" class="btn-toggle">
                    <?php if($row['availability'] == 1): ?>
                        <i class="fas fa-times"></i> Mark Unavailable
                    <?php else: ?>
                        <i class="fas fa-check"></i> Mark Available
                    <?php endif; ?>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php else: ?>

<div class="no-data">
    <i class="fas fa-syringe" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
    <h3>No Vaccines Found</h3>
    <p>Add vaccines to the system.</p>
</div>

<?php endif; ?>

<style>
.vaccines-table {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
}

.status-available {
    background: #d4edda;
    color: #155724;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-unavailable {
    background: #f8d7da;
    color: #721c24;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.btn-toggle {
    background: #3498db;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-toggle:hover {
    background: #2980b9;
}
</style>

</body>
</html>