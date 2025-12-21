<?php 
include("header.php");
include("../includes/config.php");

$parent_id = $_SESSION['user_id'];

// Get children from database WITH NEW FIELDS
$sql = "SELECT * FROM children WHERE parent_id = '$parent_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="main-content">
    <div class="header">
        <h1>My Children</h1>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div style="text-align: right; margin-bottom: 20px;">
        <a href="add_child.php" class="btn">+ Add Child</a>
    </div>

    <?php if(mysqli_num_rows($result) > 0): ?>
    
    <table class="data-table">
        <tr>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Weight</th>
            <th>Blood Group</th>
            <th>Actions</th>
        </tr>
        
        <?php while($child = mysqli_fetch_assoc($result)): 
            // Calculate age
            $birth = new DateTime($child['dob']);
            $today = new DateTime();
            $age = $birth->diff($today)->y;
        ?>
        <tr>
            <td><?php echo $child['child_name']; ?></td>
            <td><?php echo date('d M Y', strtotime($child['dob'])); ?></td>
            <td><?php echo $child['gender']; ?></td>
            <td><?php echo $child['weight'] ? $child['weight'] . ' kg' : '-'; ?></td>
            <td><?php echo $child['blood_group'] ?: '-'; ?></td>
            <td>
                <!-- <a href="vaccination_dates.php?child_id=<?php echo $child['child_id']; ?>" class="btn-small">Schedule</a> -->
                <a href="book_hospital.php?child_id=<?php echo $child['child_id']; ?>" class="btn-small">Book</a>
                <a href="view_child.php?child_id=<?php echo $child['child_id']; ?>" class="btn-small">View</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <?php else: ?>
    
    <div style="text-align: center; padding: 50px; color: #666;">
        <i class="fas fa-child" style="font-size: 50px; color: #ddd; margin-bottom: 20px;"></i>
        <h3>No Children Added</h3>
        <p>Add your first child to get started</p>
        <a href="add_child.php" class="btn">Add Child</a>
    </div>
    
    <?php endif; ?>
</div>

<style>
.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.data-table th {
    background: var(--light-color);
    padding: 15px;
    text-align: left;
    font-weight: 600;
}

.data-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.btn-small {
    padding: 5px 10px;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9rem;
    margin-right: 5px;
}

.btn-small:hover {
    background: var(--secondary-color);
}
</style>

</body>
</html>