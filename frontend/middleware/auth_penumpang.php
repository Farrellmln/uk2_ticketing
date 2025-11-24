<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Penumpang') {
    echo "<script>
        alert('Silakan login sebagai Penumpang untuk melanjutkan.');
        window.location.href='../../pages/auth/login.php';
    </script>";
    exit();
}
