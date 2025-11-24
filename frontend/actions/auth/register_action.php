<?php
include '../../app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data dari form (AMAN dari undefined index)
$nama_lengkap = mysqli_real_escape_string($connect, $_POST['nama_lengkap'] ?? '');
$username     = mysqli_real_escape_string($connect, $_POST['username'] ?? '');
$email        = mysqli_real_escape_string($connect, $_POST['email'] ?? '');
$telepon      = mysqli_real_escape_string($connect, $_POST['telepon'] ?? '');
$password     = mysqli_real_escape_string($connect, $_POST['password'] ?? '');
$role         = "Penumpang";

// Validasi wajib
if ($nama_lengkap == "" || $username == "" || $email == "" || $telepon == "" || $password == "") {
    echo "<script>
            alert('Semua data wajib diisi!');
            window.history.back();
          </script>";
    exit();
}

// Cek email
$cek_email = mysqli_query($connect, "SELECT * FROM user WHERE email = '$email'");
if (mysqli_num_rows($cek_email) > 0) {
    echo "<script>
            alert('Email sudah digunakan!');
            window.history.back();
          </script>";
    exit();
}

// Cek username
$cek_username = mysqli_query($connect, "SELECT * FROM user WHERE username = '$username'");
if (mysqli_num_rows($cek_username) > 0) {
    echo "<script>
            alert('Username sudah digunakan!');
            window.history.back();
          </script>";
    exit();
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Query insert
$query = "
    INSERT INTO user (nama_lengkap, username, email, no_hp, password, role)
    VALUES ('$nama_lengkap', '$username', '$email', '$telepon', '$password_hash', '$role')
";

if (mysqli_query($connect, $query)) {
    echo "<script>
            alert('Registrasi berhasil! Silakan login.');
            window.location.href = '../../pages/auth/login.php';
          </script>";
} else {
    echo "<script>
            alert('Terjadi kesalahan saat registrasi.');
            window.history.back();
          </script>";
}
