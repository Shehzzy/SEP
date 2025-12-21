<?php
include("./includes/header.php");
require_once './includes/config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: hospitals.php");
    exit();
}

$hospital_id = mysqli_real_escape_string($conn, $_GET['id']);

// Get hospital details
$query = "SELECT h.*, u.email as admin_email, u.name as admin_name 
          FROM hospitals h
          JOIN users u ON h.user_id = u.user_id
          WHERE h.hospital_id = '$hospital_id' AND h.status = 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='container' style='padding: 50px 0; text-align: center;'>
            <h2>Hospital Not Found</h2>
            <p>The hospital you're looking for doesn't exist or is not active.</p>
            <a href='hospitals.php' class='btn-primary'>Back to Hospitals</a>
          </div>";
    include("./includes/footer.php");
    exit();
}

$hospital = mysqli_fetch_assoc($result);
?>

<section class="page-hero">
    <div class="container">
        <h1><?php echo htmlspecialchars($hospital['hospital_name']); ?></h1>
        <p><?php echo htmlspecialchars($hospital['location']); ?></p>
    </div>
</section>

<section class="hospital-details">
    <div class="container">
        <div class="detail-card">
            <div class="detail-header">
                <h2>Hospital Information</h2>
                <span class="status-badge <?php echo $hospital['status'] == 1 ? 'active' : 'inactive'; ?>">
                    <?php echo $hospital['status'] == 1 ? 'Active' : 'Inactive'; ?>
                </span>
            </div>
            
            <div class="detail-content">
                <div class="detail-row">
                    <div class="detail-label">
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>Address:</strong>
                    </div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($hospital['address']); ?>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">
                        <i class="fas fa-city"></i>
                        <strong>City:</strong>
                    </div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($hospital['location']); ?>
                    </div>
                </div>
                
                <?php if(!empty($hospital['contact_phone'])): ?>
                <div class="detail-row">
                    <div class="detail-label">
                        <i class="fas fa-phone"></i>
                        <strong>Contact Phone:</strong>
                    </div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($hospital['contact_phone']); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(!empty($hospital['contact_email'])): ?>
                <div class="detail-row">
                    <div class="detail-label">
                        <i class="fas fa-envelope"></i>
                        <strong>Contact Email:</strong>
                    </div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($hospital['contact_email']); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <div class="detail-label">
                        <i class="fas fa-user-md"></i>
                        <strong>Administrator:</strong>
                    </div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($hospital['admin_name']); ?>
                    </div>
                </div>
            </div>
            
            <div class="detail-actions">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'parent'): ?>
                    <a href="book_appointment.php?hospital_id=<?php echo $hospital['hospital_id']; ?>" class="btn-primary">
                        <i class="fas fa-calendar-check"></i> Book Appointment
                    </a>
                <?php elseif(!isset($_SESSION['user_id'])): ?>
                    <a href="login.php?redirect=book&hospital_id=<?php echo $hospital['hospital_id']; ?>" class="btn-primary">
                        <i class="fas fa-calendar-check"></i> Login to Book Appointment
                    </a>
                <?php endif; ?>
                <a href="hospitals.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Hospitals
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.hospital-details {
    padding: 50px 0;
}

.detail-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 30px;
}

.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.status-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.detail-row {
    display: flex;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f5f5f5;
}

.detail-label {
    width: 200px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-label i {
    color: #3498db;
    width: 20px;
}

.detail-value {
    flex: 1;
    color: #555;
}

.detail-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.3s;
}

.btn-secondary:hover {
    background: #7f8c8d;
    color: white;
}

@media (max-width: 768px) {
    .detail-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .detail-label {
        width: 100%;
    }
    
    .detail-actions {
        flex-direction: column;
    }
    
    .detail-actions a {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}
</style>

<?php
include("./includes/footer.php");
?>