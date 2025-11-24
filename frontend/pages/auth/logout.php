<?php
include '../../app.php';
session_start();

// ==========================================================
// 🔹 HAPUS SEMUA SESSION
// ==========================================================
session_unset();
session_destroy();

// ==========================================================
// 🔹 REDIRECT KE HALAMAN LOGIN
// ==========================================================
echo "
    <script>
        alert('Anda berhasil logout!');
        window.location.href = '../utama/index.php';
    </script>
";
exit();
?>
