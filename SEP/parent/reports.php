<?php 
include("header.php");
include("../includes/config.php");

$parent_id = $_SESSION['user_id'];
$child_id = $_GET['child_id'] ?? 0;
$status_filter = $_GET['status'] ?? 'all';

// Get all children for dropdown
$children_sql = "SELECT * FROM children WHERE parent_id = '$parent_id'";
$children_result = mysqli_query($conn, $children_sql);

// Get reports for selected child with filters
$reports = [];
$child_name = "";
$child_dob = "";
if ($child_id > 0) {
    // Get child name for PDF
    $child_query = "SELECT child_name, dob FROM children WHERE child_id = '$child_id'";
    $child_result = mysqli_query($conn, $child_query);
    $child_data = mysqli_fetch_assoc($child_result);
    $child_name = $child_data['child_name'] ?? '';
    $child_dob = $child_data['dob'] ?? '';
    
    // Build query with status filter
    $report_sql = "SELECT v.vaccine_name, b.booking_date, b.status, r.vaccination_date, r.remarks 
                   FROM bookings b
                   JOIN vaccines v ON b.vaccine_id = v.vaccine_id
                   LEFT JOIN vaccination_reports r ON b.booking_id = r.booking_id
                   WHERE b.child_id = '$child_id'";
    
    if ($status_filter != 'all') {
        $report_sql .= " AND b.status = '$status_filter'";
    }
    
    $report_sql .= " ORDER BY b.booking_date DESC";
    
    $report_result = mysqli_query($conn, $report_sql);
    while($row = mysqli_fetch_assoc($report_result)) {
        $reports[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccination Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --light-color: #ecf0f1;
            --text-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: var(--text-color);
        }

        .main-content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
        }

        .logout-btn {
            background: #2c3e50;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #1a252f;
        }

        .filter-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .filter-row {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
        }

        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: var(--secondary-color);
        }

        .btn-secondary {
            background: #7f8c8d;
        }

        .btn-secondary:hover {
            background: #6c7b7d;
        }

        .btn-success {
            background: #27ae60;
        }

        .btn-success:hover {
            background: #219653;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .data-table th {
            background: var(--light-color);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .data-table tr:hover {
            background: #f9f9f9;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-vaccinated {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .summary-card h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .summary-card p {
            color: #666;
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 15px;
        }

        /* Loading overlay */
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            z-index: 9999;
            text-align: center;
            padding-top: 20%;
        }

        .loading-content {
            background: white;
            padding: 40px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .export-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .export-options {
                flex-direction: column;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="header">
        <h1>Vaccination Reports</h1>
        <a href="../logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Filter Section -->
    <div class="filter-box">
        <div class="filter-row">
            <div class="filter-group">
                <label><i class="fas fa-child"></i> Select Child</label>
                <select onchange="window.location='reports.php?child_id=' + this.value + '&status=<?php echo $status_filter; ?>'">
                    <option value="">-- Choose Child --</option>
                    <?php 
                    // Reset pointer to reuse result
                    mysqli_data_seek($children_result, 0);
                    while($child = mysqli_fetch_assoc($children_result)): 
                    ?>
                    <option value="<?php echo $child['child_id']; ?>" 
                        <?php echo $child_id == $child['child_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($child['child_name']); ?>
                        (DOB: <?php echo date('d/m/Y', strtotime($child['dob'])); ?>)
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <?php if($child_id > 0): ?>
            <div class="filter-group">
                <label><i class="fas fa-filter"></i> Filter by Status</label>
                <select onchange="window.location='reports.php?child_id=<?php echo $child_id; ?>&status=' + this.value">
                    <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="0" <?php echo $status_filter == '0' ? 'selected' : ''; ?>>Pending</option>
                    <option value="1" <?php echo $status_filter == '1' ? 'selected' : ''; ?>>Approved</option>
                    <option value="2" <?php echo $status_filter == '2' ? 'selected' : ''; ?>>Vaccinated</option>
                    <option value="3" <?php echo $status_filter == '3' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="fas fa-download"></i> Export Report</label>
                <div class="export-options">
                    <a href="generate_pdf.php?child_id=<?php echo $child_id; ?>&status=<?php echo $status_filter; ?>" 
                       class="btn btn-success" onclick="showLoading()">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    <button onclick="printReport()" class="btn btn-secondary">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($child_id > 0): ?>
    
    <!-- Summary Statistics -->
    <?php if(count($reports) > 0): ?>
    <?php
    $total = 0;
    $completed = 0;
    $approved = 0;
    $pending = 0;
    $rejected = 0;
    
    foreach($reports as $report) {
        $total++;
        switch($report['status']) {
            case 0: $pending++; break;
            case 1: $approved++; break;
            case 2: $completed++; break;
            case 3: $rejected++; break;
        }
    }
    ?>
    
    <div class="summary-grid">
        <div class="summary-card" style="border-top: 4px solid #3498db;">
            <h3 style="color: #3498db;"><?php echo $total; ?></h3>
            <p>Total Vaccines</p>
        </div>
        <div class="summary-card" style="border-top: 4px solid #28a745;">
            <h3 style="color: #28a745;"><?php echo $completed; ?></h3>
            <p>Completed</p>
        </div>
        <div class="summary-card" style="border-top: 4px solid #17a2b8;">
            <h3 style="color: #17a2b8;"><?php echo $approved; ?></h3>
            <p>Approved</p>
        </div>
        <div class="summary-card" style="border-top: 4px solid #ffc107;">
            <h3 style="color: #856404;"><?php echo $pending; ?></h3>
            <p>Pending</p>
        </div>
        <div class="summary-card" style="border-top: 4px solid #dc3545;">
            <h3 style="color: #dc3545;"><?php echo $rejected; ?></h3>
            <p>Rejected</p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Reports Table -->
    <?php if(count($reports) > 0): ?>
    
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h2><i class="fas fa-file-medical-alt"></i> Vaccination Records for <?php echo htmlspecialchars($child_name); ?></h2>
        <small><?php echo count($reports); ?> records found</small>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Vaccine Name</th>
                <th>Scheduled Date</th>
                <th>Vaccinated Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reports as $report): 
                $status = $report['status'];
                $status_text = ['Pending', 'Approved', 'Vaccinated', 'Rejected'][$status];
                $status_class = ['status-pending', 'status-approved', 'status-vaccinated', 'status-rejected'][$status];
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($report['vaccine_name']); ?></strong></td>
                <td><?php echo date('d M Y', strtotime($report['booking_date'])); ?></td>
                <td>
                    <?php if($report['vaccination_date']): ?>
                        <?php echo date('d M Y', strtotime($report['vaccination_date'])); ?>
                    <?php else: ?>
                        <span style="color: #999;">-</span>
                    <?php endif; ?>
                </td>
                <td><span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                <td><?php echo $report['remarks'] ? htmlspecialchars($report['remarks']) : '<span style="color: #999;">-</span>'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php else: ?>
    
    <div class="empty-state">
        <i class="fas fa-file-medical-alt"></i>
        <h3>No Records Found</h3>
        <p>No vaccination records found for <?php echo htmlspecialchars($child_name); ?> with the selected filter.</p>
        <p style="margin-top: 15px;">
            <a href="reports.php?child_id=<?php echo $child_id; ?>&status=all" class="btn">
                <i class="fas fa-eye"></i> Show All Records
            </a>
        </p>
    </div>
    
    <?php endif; ?>
    
    <?php else: ?>
    
    <!-- No Child Selected -->
    <div class="empty-state">
        <h3>Select a Child</h3>
        <p>Please select a child from the dropdown above to view vaccination reports.</p>
    </div>
    
    <?php endif; ?>
</div>

<!-- Loading Overlay -->
<div id="loading" class="loading">
    <div class="loading-content">
        <div class="spinner"></div>
        <h3>Generating PDF Report</h3>
        <p>Please wait while we prepare your vaccination report...</p>
        <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">
            <i class="fas fa-info-circle"></i> This may take a few moments.
        </p>
    </div>
</div>

<script>
// Show loading overlay
function showLoading() {
    document.getElementById('loading').style.display = 'block';
    // Hide loading after 5 seconds (in case PDF doesn't start)
    setTimeout(function() {
        document.getElementById('loading').style.display = 'none';
    }, 5000);
}

// Print report
function printReport() {
    if (confirm('Print vaccination report?')) {
        window.print();
    }
}

// Hide loading when PDF download starts
window.addEventListener('load', function() {
    document.getElementById('loading').style.display = 'none';
});

// Auto-hide loading if user navigates away
window.addEventListener('beforeunload', function() {
    document.getElementById('loading').style.display = 'none';
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        printReport();
    }
    if (e.ctrlKey && e.key === 'r') {
        window.location.reload();
    }
});

// Show child info tooltip
const childSelect = document.querySelector('select[onchange*="child_id"]');
if (childSelect) {
    childSelect.addEventListener('change', function() {
        if (this.value) {
            showLoading();
        }
    });
}
</script>

</body>
</html>