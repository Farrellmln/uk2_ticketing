<?php
require '../../app.php';
require '../../fpdf186/fpdf.php';

$id = intval($_GET['id_pemesanan'] ?? 0);

// =================== QUERY DATA PEMESANAN ==================
$q = mysqli_query($connect, "
    SELECT p.id_pemesanan, p.jumlah_tiket, p.total_harga, p.kursi,
           r.asal, r.tujuan, r.jadwal_berangkat,
           t.nama_transportasi, t.jenis,
           u.nama_lengkap
    FROM pemesanan p
    JOIN rute r ON p.id_rute = r.id_rute
    JOIN transportasi t ON r.id_transportasi = t.id_transportasi
    JOIN user u ON p.id_user = u.id_user
    WHERE p.id_pemesanan = $id
");

if (mysqli_num_rows($q) == 0) {
    die("Tiket tidak ditemukan");
}

$data = mysqli_fetch_assoc($q);

// Format tanggal
$jadwal = date("d M Y - H:i", strtotime($data['jadwal_berangkat']));

// Rute fix
$rute = $data['asal'] . " - " . $data['tujuan'];

// Logo path
$logoPath = '../../../storages/navbar/goticket.png';

// =================== MULAI PDF ===================
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// =================== HEADER KUNING ===================
$pdf->SetFillColor(255, 176, 0);
$pdf->Rect(0, 0, 210, 40, 'F');

// Teks E-ticket
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetXY(0, 12);
$pdf->Cell(210, 15, "E - TICKET", 0, 1, 'C');

// =================== BOX DETAIL ===================
$pdf->SetY(55);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, "Detail Tiket", 0, 1, 'L');

$pdf->SetFont('Arial', '', 12);

function field($pdf, $label, $value) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 8, $label, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(5, 8, ":", 0, 0);
    $pdf->Cell(100, 8, $value, 0, 1);
}

$pdf->Ln(3);

// DATA PEMESANAN
field($pdf, "Nama", ucfirst($data['nama_lengkap']));
field($pdf, "Transportasi", $data['nama_transportasi'] . " (" . $data['jenis'] . ")");
field($pdf, "Rute", $rute);
field($pdf, "Berangkat", $jadwal);
field($pdf, "Jumlah Tiket", $data['jumlah_tiket']);

// =================== FIELD KURSI BARU ===================
field($pdf, "Kursi", $data['kursi'] ?: '-');

field($pdf, "Kode Booking", "#" . $data['id_pemesanan']);

// =================== FOOTER (LOGO + TEXT) ===================
$pdf->Ln(20);

// LOGO DI ATAS TULISAN
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 85, 215, 40);
}

$pdf->SetY(260);

// FOOTER TEXT
$pdf->SetFont('Arial', 'I', 11);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(0, 7, "Terima kasih telah menggunakan Goticket.", 0, 1, 'C');
$pdf->Cell(0, 7, "Harap datang sesuai jadwal keberangkatan.", 0, 1, 'C');

// =================== DOWNLOAD PDF ===================
$pdf->Output("D", "tiket_goticket_$id.pdf");
exit;

?>
