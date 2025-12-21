<?php
require_once('../includes/config.php');
require_once('../assets/tcpdf/tcpdf.php');

// Get report type
$type = $_GET['type'] ?? 'overview';

// Create PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document info
$pdf->SetCreator('Vaccination System');
$pdf->SetAuthor('Academic Project');
$pdf->SetTitle(ucfirst($type) . ' Report');
$pdf->SetSubject('Vaccination Report');

// Remove header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add page
$pdf->AddPage();

// Add logo/header
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, 'VACCINATION SYSTEM REPORT', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, ucfirst($type) . ' Report - ' . date('d/m/Y'), 0, 1, 'C');
$pdf->Ln(10);

// Generate report based on type
switch ($type) {
    case 'overview':
        generateOverview($pdf, $conn);
        break;

    case 'hospitals':
        generateHospitals($pdf, $conn);
        break;

    case 'parents':
        generateParents($pdf, $conn);
        break;
}

// Output PDF
$pdf->Output('report_' . $type . '.pdf', 'I');

// Function 1: Overview Report
function generateOverview($pdf, $conn)
{
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'System Overview', 0, 1);
    $pdf->Ln(5);

    // Get stats
    $kids = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM children"))['count'];
    $vacc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM bookings WHERE status=2"))['count'];
    $hosp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM hospitals"))['count'];
    $parents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='parent'"))['count'];

    $pdf->SetFont('helvetica', '', 12);

    $data = [
        ['Total Children', $kids],
        ['Completed Vaccinations', $vacc],
        ['Hospitals', $hosp],
        ['Registered Parents', $parents]
    ];

    foreach ($data as $row) {
        $pdf->Cell(100, 8, $row[0] . ':', 0, 0);
        $pdf->Cell(0, 8, $row[1], 0, 1);
    }

    $pdf->Ln(10);

    // Recent vaccinations
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Recent Vaccinations', 0, 1);

    $recent = mysqli_query($conn, "
        SELECT c.child_name as child, v.vaccine_name as vaccine, h.hospital_name as hospital, b.booking_date 
        FROM bookings b
        JOIN children c ON b.child_id = c.child_id
        JOIN vaccines v ON b.vaccine_id = v.vaccine_id
        JOIN hospitals h ON b.hospital_id = h.hospital_id
        WHERE b.status = 2
        ORDER BY b.booking_date DESC
        LIMIT 10
    ");

    $pdf->SetFont('helvetica', '', 10);
    while ($row = mysqli_fetch_assoc($recent)) {
        $pdf->Cell(0, 6, "• {$row['child']} - {$row['vaccine']} at {$row['hospital']} on " . date('d/m/Y', strtotime($row['booking_date'])), 0, 1);
    }
}

// Function 2: Hospital Report
function generateHospitals($pdf, $conn)
{
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Hospital Performance', 0, 1);
    $pdf->Ln(5);

    // Hospital stats
    $hospitals = mysqli_query($conn, "
        SELECT h.hospital_name, 
               COUNT(b.booking_id) as total_vaccines,
               COUNT(CASE WHEN b.status=2 THEN 1 END) as completed,
               COUNT(CASE WHEN b.status=1 THEN 1 END) as pending
        FROM hospitals h
        LEFT JOIN bookings b ON h.hospital_id = b.hospital_id
        GROUP BY h.hospital_id
        ORDER BY completed DESC
    ");

    $pdf->SetFont('helvetica', '', 11);

    while ($h = mysqli_fetch_assoc($hospitals)) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, $h['hospital_name'], 0, 1);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(60, 6, 'Completed Vaccinations:', 0, 0);
        $pdf->Cell(0, 6, $h['completed'], 0, 1);

        $pdf->Cell(60, 6, 'Pending Vaccinations:', 0, 0);
        $pdf->Cell(0, 6, $h['pending'], 0, 1);

        $pdf->Cell(60, 6, 'Total Assigned:', 0, 0);
        $pdf->Cell(0, 6, $h['total_vaccines'], 0, 1);

        $pdf->Ln(5);
    }
}

function generateParents($pdf, $conn) {
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Parents Report', 0, 1);
    $pdf->Ln(5);
    
    // Total parents
    $total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='parent'"))['count'];
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 8, "Total Registered Parents: $total", 0, 1);
    $pdf->Ln(5);
    
    // Get all parents
    $parents = mysqli_query($conn, "
        SELECT u.user_id, u.name, u.email
        FROM users u
        WHERE u.role = 'parent'
        ORDER BY u.name
        LIMIT 20
    ");
    
    // Create table header
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(80, 8, 'Parent Name', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Children', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Vaccinations', 1, 1, 'C');
    
    $pdf->SetFont('helvetica', '', 10);
    
    // Check if there are any parents
    if(mysqli_num_rows($parents) > 0) {
        while($p = mysqli_fetch_assoc($parents)) {
            $parent_id = $p['user_id'];
            
            // Count children for this parent
            $children_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM children WHERE parent_id = $parent_id");
            $children_count = mysqli_fetch_assoc($children_query)['count'];
            
            // Count vaccinations for this parent's children
            $vacc_query = mysqli_query($conn, "
                SELECT COUNT(*) as count 
                FROM bookings b
                JOIN children c ON b.child_id = c.child_id
                WHERE c.parent_id = $parent_id AND b.status = 2
            ");
            $vacc_count = mysqli_fetch_assoc($vacc_query)['count'];
            
            // Display row
            $pdf->Cell(80, 8, substr($p['name'], 0, 25), 1, 0);
            $pdf->Cell(40, 8, $children_count, 1, 0, 'C');
            $pdf->Cell(40, 8, $vacc_count, 1, 1, 'C');
        }
    } else {
        $pdf->Cell(160, 8, 'No parent data found', 1, 1, 'C');
    }
}

?>