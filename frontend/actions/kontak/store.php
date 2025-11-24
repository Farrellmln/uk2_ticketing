<?php
// PERBAIKAN 1: Ganti $_SERVER['DOCUMENT_ROOT'] dengan __DIR__ untuk include yang lebih akurat
// __DIR__ saat ini di backend/actions/kontak. Jalur harus naik 3 level ke root proyek
include __DIR__ . '/../../../config/connection.php';
include __DIR__ . '/../../../config/escapeString.php';

// cek form yang dikirim dengan name="tombol"
if(isset($_POST['tombol'])){
    // ... (kode processing data tetap sama) ...
    $name    = escapeString($_POST['name']);
    $email   = escapeString($_POST['email']);
    $telepon = escapeString($_POST['telepon']);
    $subjek  = escapeString($_POST['subjek']);
    $message = escapeString($_POST['message']);
    $created_at = date('Y-m-d H:i:s'); // otomatis waktu sekarang

    // Pastikan nama tabel sesuai di database
    $qInsert = "INSERT INTO message(name, email, telepon, subjek, message, created_at) 
                VALUES ('$name', '$email', '$telepon', '$subjek', '$message', '$created_at')";

    if(mysqli_query($connect, $qInsert)){
        echo "
            <script>
                alert('Data Berhasil Ditambah');
                // PERBAIKAN 2: Tambahkan 'frontend/' di jalur redirect
                window.location.href = '../../pages/kontak/index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data Gagal Ditambah: ".mysqli_error($connect)."');
                // PERBAIKAN 2: Tambahkan 'frontend/' di jalur redirect
                window.location.href = '../../pages/kontak/index.php';
            </script>
        ";
    }
} else {
    // jika akses langsung file ini tanpa submit form
    // PERBAIKAN 3: Tambahkan 'frontend/' di jalur redirect header
    header("Location: ../../pages/kontak/index.php");
    exit;
}
?>

<section id="contact" class="contact section">
    </section>