<?php
include '../../app.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil input
    $nama_lengkap = mysqli_real_escape_string($connect, $_POST['nama_lengkap']);
    $username     = mysqli_real_escape_string($connect, $_POST['username']);
    $email        = mysqli_real_escape_string($connect, $_POST['email']);
    $no_hp        = mysqli_real_escape_string($connect, $_POST['no_hp']);
    $role         = mysqli_real_escape_string($connect, $_POST['role']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek username sudah ada
    $cek = mysqli_query($connect, 
        "SELECT username FROM user WHERE username = '$username'"
    );

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Username sudah digunakan!');
                window.location='../../pages/registrasi_petugas/create.php';
              </script>";
        exit;
    }

    // Query insert
    $query = "
        INSERT INTO user (nama_lengkap, username, password, email, no_hp, role)
        VALUES ('$nama_lengkap', '$username', '$password', 
                " . ($email ? "'$email'" : "NULL") . ", 
                '$no_hp', '$role')
    ";

    if (mysqli_query($connect, $query)) {
        echo "<script>
                alert('User berhasil ditambahkan!');
                window.location='../../pages/registrasi_petugas/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data user!');
                window.location='../../pages/registrasi_petugas/create.php';
              </script>";
    }
}
?>
