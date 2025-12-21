<?php
require_once 'header.php';

// Simple query - hospital only sees approved (1) or vaccinated (2) appointments
$sql = "SELECT b.*, c.child_name, v.vaccine_name, u.name as parent_name, u.email
        FROM bookings b
        JOIN children c ON b.child_id = c.child_id
        JOIN vaccines v ON b.vaccine_id = v.vaccine_id
        JOIN users u ON c.parent_id = u.user_id
        WHERE b.hospital_id = '$hospital_id' 
        AND b.status IN (1, 2)
        ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="welcome-box">
    <h2>Appointments</h2>
    <p>View all approved appointments</p>
</div>

<div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
    <table>
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Vaccine</th>
                <th>Date</th>
                <th>Parent</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['child_name']; ?></td>
                        <td><?php echo $row['vaccine_name']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></td>
                        <td>
                            <?php echo $row['parent_name']; ?><br>
                            <small><?php echo $row['email']; ?></small>
                        </td>
                        <td>
                            <?php 
                            if($row['status'] == 1) {
                                echo '<span class="status status-approved">Approved</span>';
                            } else {
                                echo '<span class="status status-vaccinated">Vaccinated</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 1): ?>
                                <a href="update_status.php?booking_id=<?php echo $row['booking_id']; ?>" 
                                   class="btn" style="padding: 5px 10px; font-size: 0.8rem;">
                                    Update
                                </a>
                            <?php else: ?>
                                <span class="btn" style="background: #95a5a6; padding: 5px 10px; font-size: 0.8rem;">
                                    Done
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; color: #bdc3c7; margin-bottom: 10px;"></i>
                        <p>No appointments found</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>