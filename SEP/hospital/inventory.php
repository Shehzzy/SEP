<?php
require_once 'header.php';

// Handle inventory update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $vaccine_id = $_POST['vaccine_id'];
    $new_quantity = $_POST['quantity'];
    
    // Debug: Show what we're trying to update
    echo "<!-- Debug: Updating vaccine $vaccine_id to quantity $new_quantity for hospital $hospital_id -->";
    
    // First check if record exists
    $check_sql = "SELECT * FROM hospital_vaccines 
                  WHERE hospital_id = '$hospital_id' 
                  AND vaccine_id = '$vaccine_id'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update existing
        $sql = "UPDATE hospital_vaccines 
                SET quantity = '$new_quantity' 
                WHERE hospital_id = '$hospital_id' 
                AND vaccine_id = '$vaccine_id'";
    } else {
        // Insert new
        $sql = "INSERT INTO hospital_vaccines (hospital_id, vaccine_id, quantity) 
                VALUES ('$hospital_id', '$vaccine_id', '$new_quantity')";
    }
    
    if (mysqli_query($conn, $sql)) {
        $message = "Stock updated!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Get inventory
$sql = "SELECT v.vaccine_id, v.vaccine_name, 
        IFNULL(hv.quantity, 0) as stock 
        FROM vaccines v
        LEFT JOIN hospital_vaccines hv ON v.vaccine_id = hv.vaccine_id 
        AND hv.hospital_id = '$hospital_id'
        ORDER BY v.vaccine_name";
$result = mysqli_query($conn, $sql);
?>

<div class="welcome-box">
    <h2>Vaccine Inventory</h2>
    <p>Hospital ID: <?php echo $hospital_id; ?></p>
</div>

<?php if(isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if(isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <table>
        <thead>
            <tr>
                <th>Vaccine Name</th>
                <th>Current Stock</th>
                <th>Status</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <?php 
                $stock = $row['stock'];
                if ($stock == 0) {
                    $status = 'Out of Stock';
                    $class = 'status-rejected';
                } elseif ($stock < 10) {
                    $status = 'Low Stock';
                    $class = 'status-pending';
                } else {
                    $status = 'In Stock';
                    $class = 'status-approved';
                }
                ?>
                <tr>
                    <td><?php echo $row['vaccine_name']; ?></td>
                    <td><?php echo $stock; ?></td>
                    <td><span class="status <?php echo $class; ?>"><?php echo $status; ?></span></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="vaccine_id" value="<?php echo $row['vaccine_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $stock; ?>" min="0" style="width: 70px;">
                            <button type="submit" name="update" class="btn">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>