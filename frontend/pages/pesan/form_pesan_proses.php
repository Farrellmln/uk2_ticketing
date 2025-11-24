<?php
include '../../app.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Cek login
    if (!isset($_SESSION['id_user'])) {
        echo "<script>alert('Harap login terlebih dahulu!'); window.location='../login.php';</script>";
        exit();
    }

    $id_user        = $_SESSION['id_user'];
    $id_rute        = intval($_POST['id_rute']);
    $jumlah_tiket   = intval($_POST['jumlah_tiket']);
    $id_transportasi= intval($_POST['id_transportasi']);
    $kursiDipilih   = isset($_POST['kursi']) ? trim($_POST['kursi']) : '';

    if ($jumlah_tiket <= 0) {
        echo "<script>alert('Jumlah tiket tidak valid.'); history.back();</script>";
        exit();
    }

    // Ambil harga rute (pastikan valid)
    $stmt = $connect->prepare("SELECT harga FROM rute WHERE id_rute = ?");
    $stmt->bind_param("i", $id_rute);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "<script>alert('Rute tidak ditemukan!'); history.back();</script>";
        exit();
    }

    $harga       = intval($result['harga']);
    $total_harga = $jumlah_tiket * $harga;

    // ================== LOGIKA KURSI ==================
    $kursiList   = [];   // Array kursi yang akan disimpan & diupdate
    $kursiString = "";   // String untuk simpan di tabel pemesanan

    if ($jumlah_tiket == 1) {
        // HARUS pilih kursi
        if ($kursiDipilih === '') {
            echo "<script>alert('Silakan pilih kursi terlebih dahulu.'); history.back();</script>";
            exit();
        }

        // Pastikan kursi masih kosong
        $cek = $connect->prepare("
            SELECT status_kursi 
            FROM kursi 
            WHERE id_transportasi = ? AND nomor_kursi = ?
        ");
        $cek->bind_param("is", $id_transportasi, $kursiDipilih);
        $cek->execute();
        $row = $cek->get_result()->fetch_assoc();

        if (!$row || $row['status_kursi'] !== 'kosong') {
            echo "<script>alert('Kursi yang dipilih sudah terisi, silakan pilih kursi lain.'); history.back();</script>";
            exit();
        }

        $kursiList[]   = $kursiDipilih;
        $kursiString   = $kursiDipilih;

    } else {
        // AUTO SEAT: ambil kursi kosong paling awal sesuai jumlah_tiket
        $cek = $connect->prepare("
            SELECT nomor_kursi 
            FROM kursi 
            WHERE id_transportasi = ? AND status_kursi = 'kosong'
            ORDER BY nomor_kursi ASC
            LIMIT ?
        ");
        $cek->bind_param("ii", $id_transportasi, $jumlah_tiket);
        $cek->execute();
        $resKursi = $cek->get_result();

        while ($r = $resKursi->fetch_assoc()) {
            $kursiList[] = $r['nomor_kursi'];
        }

        if (count($kursiList) < $jumlah_tiket) {
            echo "<script>alert('Kursi kosong tidak mencukupi untuk jumlah tiket yang dipesan.'); history.back();</script>";
            exit();
        }

        $kursiString = implode(',', $kursiList);
    }

    // ================== INSERT PEMESANAN ==================
    $stmt = $connect->prepare("
        INSERT INTO pemesanan (id_user, id_rute, jumlah_tiket, total_harga, kursi, status_pemesanan)
        VALUES (?, ?, ?, ?, ?, 'Menunggu Pembayaran')
    ");
    $stmt->bind_param("iiiis", $id_user, $id_rute, $jumlah_tiket, $total_harga, $kursiString);
    $stmt->execute();

    $id_pemesanan = $stmt->insert_id;

    // ================== UPDATE STATUS KURSI ==================
    $upd = $connect->prepare("
        UPDATE kursi 
        SET status_kursi = 'dipesan'
        WHERE id_transportasi = ? AND nomor_kursi = ?
    ");
    foreach ($kursiList as $k) {
        $upd->bind_param("is", $id_transportasi, $k);
        $upd->execute();
    }

    // Redirect
    echo "<script>
            alert('Pemesanan berhasil! Silakan lanjut untuk melihat detail pemesanan.');
            window.location.href = 'detail_pesanan.php?id_pemesanan=$id_pemesanan';
          </script>";
    exit();
}
?>
