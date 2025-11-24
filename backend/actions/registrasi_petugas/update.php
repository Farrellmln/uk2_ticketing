<?php
include '../../app.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_user       = mysqli_real_escape_string($connect, $_POST['id_user']);
    $nama_lengkap  = mysqli_real_escape_string($connect, $_POST['nama_lengkap']);
    $username      = mysqli_real_escape_string($connect, $_POST['username']);
    $email         = mysqli_real_escape_string($connect, $_POST['email']);
    $no_hp         = mysqli_real_escape_string($connect, $_POST['no_hp']);
    $role          = mysqli_real_escape_string($connect, $_POST['role']);
    $password      = $_POST['password']; // jangan escape dulu

    // CEK USERNAME DUPLIKAT (kecuali dirinya sendiri)
    $cekUsername = mysqli_query($connect, 
        "SELECT username FROM user 
         WHERE username='$username' 
         AND id_user!='$id_user'"
    );

    if (mysqli_num_rows($cekUsername) > 0) {
        echo "<script>alert('Username sudah digunakan pengguna lain!'); 
              window.location='../../pages/registrasi_petugas/edit.php?id=$id_user';</script>";
        exit;
    }

    // CEK EMAIL DUPLIKAT (kecuali dirinya sendiri)
    if (!empty($email)) {
        $cekEmail = mysqli_query($connect,
            "SELECT email FROM user
             WHERE email='$email'
             AND id_user!='$id_user'"
        );

        if (mysqli_num_rows($cekEmail) > 0) {
            echo "<script>alert('Email sudah digunakan!'); 
                  window.location='../../pages/registrasi_petugas/edit.php?id=$id_user';</script>";
            exit;
        }
    }

    // JIKA PASSWORD TIDAK KOSONG → HASH & UPDATE PASSWORD
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $update = "
        UPDATE user SET
            nama_lengkap = '$nama_lengkap',
            username     = '$username',
            email        = '$email',
            no_hp        = '$no_hp',
            role         = '$role',
            password     = '$passwordHash'
        WHERE id_user = '$id_user'
        ";
    } 
    else {
        // TANPA UPDATE PASSWORD
        $update = "
        UPDATE user SET
            nama_lengkap = '$nama_lengkap',
            username     = '$username',
            email        = '$email',
            no_hp        = '$no_hp',
            role         = '$role'
        WHERE id_user = '$id_user'
        ";
    }

    if (mysqli_query($connect, $update)) {
        echo "<script>alert('Data user berhasil diperbarui!'); 
              window.location='../../pages/registrasi_petugas/index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!'); 
              window.location='../../pages/registrasi_petugas/edit.php?id=$id_user';</script>";
    }

}
?>
