<?php
include "koneksi.php"; // Include your database connection file
require_once('fpdf.php'); // Include the FPDF library (ensure the correct path to fpdf.php)

header("Content-type: application/pdf");  // Set the content type to PDF
header("Content-Disposition: attachment; filename=Export_Data_Pengunjung.pdf");  // Name of the generated PDF file
header("Pragma: no-cache");
header("Expires: 0");

// Convert image to base64 function (if you need to use base64 images later)
function getImageBase64($imagePath) {
    if (file_exists($imagePath)) {
        $imageData = file_get_contents($imagePath);
        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }
    return '';
    
}

// Initialize PDF
$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 15); // Automatic page break
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Add a title
$pdf->Cell(0, 10, 'Rekapitulasi Data Pengunjung', 0, 1, 'C'); // Title of the report

// Set table headers
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'No.', 1, 0, 'C'); // Column for No.
$pdf->Cell(30, 10, 'Tanggal', 1, 0, 'C'); // Column for Tanggal (Date)
$pdf->Cell(40, 10, 'Nama', 1, 0, 'C'); // Column for Nama (Name)
$pdf->Cell(40, 10, 'Instansi', 1, 0, 'C'); // Column for Instansi (Agency)
$pdf->Cell(40, 10, 'Keperluan', 1, 0, 'C'); // Column for Keperluan (Purpose)
$pdf->Cell(30, 10, 'No Hp', 1, 0, 'C'); // Column for No Hp (Phone Number)
$pdf->Cell(30, 10, 'Foto  ', 1, 1, 'C'); // Column for Foto (Photo)

// Reset font for data rows
$pdf->SetFont('Arial', '', 10);

// Fetch data from the database
$tgl1 = $_POST['tanggala'];  // Date start
$tgl2 = $_POST['tanggalb'];  // Date end

$tampil = mysqli_query($koneksi, "SELECT * FROM tamu WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' ORDER BY tanggal ASC");
$no = 1;  // Start with number 1 for "No." column

// Loop through data and display in table
while ($data = mysqli_fetch_array($tampil)) {
    // Add data to table
    $pdf->Cell(10, 10, $no++, 1, 0, 'C'); // No.
    $pdf->Cell(30, 10, $data['tanggal'], 1, 0, 'C'); // Tanggal
    $pdf->Cell(40, 10, $data['nama'], 1, 0, 'C'); // Nama
    $pdf->Cell(40, 10, $data['alamat'], 1, 0, 'C'); // Instansi (address)
    $pdf->Cell(40, 10, $data['tujuan'], 1, 0, 'C'); // Keperluan (purpose)
    $pdf->Cell(30, 10, $data['nope'], 1, 0, 'C'); // No Hp
    // $pdf->Cell(30, 10, $data[''], 1, 0, 'C'); // No Hp
    

  // Assuming the image is in a directory relative to your script
$imagePath = 'C:\xampp2\htdocs\bukutamu\gambar' . ltrim($data['user_foto'], );

// Check if the image exists
if (file_exists($imagePath)) {
    // Image exists, display it
    $pdf->Image($imagePath, $pdf->GetX(), $pdf->GetY(), 10, 10);
    $pdf->Cell(30, 10, '', 1, 1, 'C');
} else {
    // Image doesn't exist, display a message
    $pdf->Cell(30, 10, 'No Image', 1, 1, 'C');
}

}

// Output the PDF
$pdf->Output('I', 'Export_Data_Pengunjung.pdf'); // Output to browser
?>


