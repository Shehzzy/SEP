<?php
require_once 'header.php';

$booking_id = $_GET['booking_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks'] ?? '');
    
    // Update booking status
    $update_sql = "UPDATE bookings SET status = '$status' WHERE booking_id = '$booking_id' 
                   AND hospital_id = '$hospital_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        if ($status == '2') { // Vaccinated
            // Check if report already exists
            $check_report = "SELECT report_id FROM vaccination_reports WHERE booking_id = '$booking_id'";
            $report_result = mysqli_query($conn, $check_report);
            
            if (mysqli_num_rows($report_result) == 0) {
                $report_sql = "INSERT INTO vaccination_reports (booking_id, vaccination_date, remarks) 
                               VALUES ('$booking_id', CURDATE(), '$remarks')";
                mysqli_query($conn, $report_sql);
            }
        }
        $success = "Vaccination status updated successfully!";
    } else {
        $error = "Error updating status: " . mysqli_error($conn);
    }
}

// Fetch booking details
if ($booking_id > 0) {
    $booking_sql = "SELECT b.*, c.child_name, c.dob, v.vaccine_name, u.name as parent_name, u.email as parent_email
                    FROM bookings b
                    JOIN children c ON b.child_id = c.child_id
                    JOIN vaccines v ON b.vaccine_id = v.vaccine_id
                    JOIN users u ON c.parent_id = u.user_id
                    WHERE b.booking_id = '$booking_id' AND b.hospital_id = '$hospital_id'";
    $booking_result = mysqli_query($conn, $booking_sql);
    $booking = mysqli_fetch_assoc($booking_result);
}

// Fetch appointments that need status update
$appointments_sql = "SELECT b.*, c.child_name, v.vaccine_name, u.name as parent_name
                     FROM bookings b
                     JOIN children c ON b.child_id = c.child_id
                     JOIN vaccines v ON b.vaccine_id = v.vaccine_id
                     JOIN users u ON c.parent_id = u.user_id
                     WHERE b.hospital_id = '$hospital_id' 
                     AND b.status IN (0, 1)
                     AND b.booking_date <= CURDATE()
                     ORDER BY b.booking_date ASC";
$appointments_result = mysqli_query($conn, $appointments_sql);
?>

<?php if(isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if(isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if($booking_id > 0 && $booking): ?>
    <!-- Update Single Booking -->
    <div class="welcome-box">
        <h2>Update Vaccine Status for <?php echo $booking['child_name']; ?></h2>
        <p>Appointment Date: <?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></p>
    </div>

    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <form method="POST" action="">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            
            <div class="form-group">
                <label>Child Information</label>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
                    <p><strong>Name:</strong> <?php echo $booking['child_name']; ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo date('F j, Y', strtotime($booking['dob'])); ?></p>
                    <p><strong>Parent:</strong> <?php echo $booking['parent_name']; ?> (<?php echo $booking['parent_email']; ?>)</p>
                </div>
            </div>

            <div class="form-group">
                <label>Vaccine</label>
                <input type="text" value="<?php echo $booking['vaccine_name']; ?>" readonly style="background: #f8f9fa;">
            </div>

            <div class="form-group">
                <label>Appointment Date</label>
                <input type="text" value="<?php echo date('F j, Y', strtotime($booking['booking_date'])); ?>" readonly style="background: #f8f9fa;">
            </div>

            <div class="form-group">
                <label>Current Status</label>
                <?php 
                $status_text = ['Pending', 'Approved', 'Vaccinated', 'Rejected'];
                $current_status = $status_text[$booking['status']] ?? 'Unknown';
                ?>
                <input type="text" value="<?php echo $current_status; ?>" readonly style="background: #f8f9fa;">
            </div>

            <div class="form-group">
                <label>Update Status *</label>
                <select name="status" required>
                    <option value="">Select Status</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                    <option value="2">Vaccinated</option>
                    <option value="3">Rejected</option>
                </select>
            </div>

            <div class="form-group">
                <label>Remarks (Optional)</label>
                <textarea name="remarks" rows="3" placeholder="Enter any remarks about the vaccination..."></textarea>
            </div>

            <button type="submit" class="btn">Update Status</button>
            <a href="update_status.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

<?php else: ?>
    <!-- List of appointments needing update -->
    <div class="welcome-box">
        <h2>Update Vaccine Status</h2>
        <p>Select an appointment to update its vaccination status</p>
    </div>

    <div class="filter-bar">
        <div style="flex: 1;">
            <input type="text" id="searchInput" placeholder="Search by child name, vaccine, or parent..." style="max-width: 400px;">
        </div>
        <select id="statusFilter">
            <option value="">All Status</option>
            <option value="0">Pending</option>
            <option value="1">Approved</option>
        </select>
    </div>

    <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <table id="appointmentsTable">
            <thead>
                <tr>
                    <th>Child Name</th>
                    <th>Vaccine</th>
                    <th>Appointment Date</th>
                    <th>Parent</th>
                    <th>Current Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($appointments_result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($appointments_result)): ?>
                        <tr>
                            <td><?php echo $row['child_name']; ?></td>
                            <td><?php echo $row['vaccine_name']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></td>
                            <td><?php echo $row['parent_name']; ?></td>
                            <td>
                                <?php 
                                $status_class = $row['status'] == 0 ? 'status-pending' : 'status-approved';
                                $status_text = $row['status'] == 0 ? 'Pending' : 'Approved';
                                ?>
                                <span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <a href="update_status.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">
                                    Update Status
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <h3>No appointments need status update</h3>
                            <p>All appointments are up to date</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Simple search and filter functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            filterTable();
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            filterTable();
        });

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#appointmentsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const rowStatus = row.querySelector('.status').textContent.toLowerCase();
                const statusMatch = status === '' || 
                    (status === '0' && rowStatus === 'pending') ||
                    (status === '1' && rowStatus === 'approved');
                
                if (text.includes(search) && statusMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
<?php endif; ?>