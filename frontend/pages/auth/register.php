<?php
session_start();

if (isset($_SESSION['id_user'])) {
    echo "
      <script>
        alert('Anda sudah login, logout terlebih dahulu.');
        window.location.href = '../utama/index.php';
      </script>
    ";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | Goticket</title>
<link rel="icon" type="image/png" sizes="16x16" href="../../../storages/navbar/g.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <style>
        :root{
            --goticket-blue: #004c84;
            --goticket-yellow: #f9b233;
            --card-bg: #ffffff;
            --page-bg-start: #f4f8ff;
            --page-bg-end: #e9f2ff;
        }

        /* reset & box sizing */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            background: linear-gradient(135deg, var(--page-bg-start) 0%, var(--page-bg-end) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
        }

        /* Container supaya tidak nabrak elemen sebelah (sidebar/header) */
        .page-wrap {
            width: 100%;
            padding: 36px 20px;
            display: flex;
            justify-content: center;
        }

        .register-container {
            width: 100%;
            max-width: 520px;      /* sedikit lebih luas tapi tetap terbatas */
            margin: 0 auto;
        }

        .register-box {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 36px 30px 38px;
            text-align: center;
            border-top: 6px solid var(--goticket-yellow);
            box-shadow: 0 10px 35px rgba(10,30,60,0.08);
            animation: fadeIn 0.45s ease;
            position: relative;    /* penting untuk z-index */
            z-index: 5;            /* pastikan tampil di depan */
        }

        /* LOGO */
        .logo-goticket {
            width: 250px;
            max-width: 100%;
            height: auto;
            margin: 0 auto 12px;
            display: block;
        }

        h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: var(--goticket-blue);
        }

        p.lead {
            margin-top: 6px;
            margin-bottom: 22px;
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 14px;
            text-align: left;
        }

        label {
            font-weight: 600;
            font-size: 13.5px;
            margin-bottom: 6px;
            color: var(--goticket-blue);
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1.4px solid #cfd9e3;
            font-size: 14px;
            transition: border-color .18s ease, box-shadow .18s ease, transform .08s ease;
            background: #fbfdff;
        }

        input:focus {
            border-color: var(--goticket-yellow);
            box-shadow: 0 0 8px rgba(249,178,51,0.18);
            outline: none;
            transform: translateY(-1px);
        }

        .register-btn {
            width: 100%;
            padding: 12px 0;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            background: var(--goticket-yellow);
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background .2s ease, transform .12s ease;
        }

        .register-btn:hover {
            background: #e19d24;
            transform: translateY(-2px);
        }

        .login-link {
            margin-top: 14px;
            font-size: 14px;
            color: #444;
        }

        .login-link a {
            color: var(--goticket-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover { text-decoration: underline; }

        @media (max-width: 640px){
            .register-box { padding: 22px; }
            .logo-goticket { width: 200px; margin-bottom:10px; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Safety: jika page layout punya column/sidebars, beri ruang ekstra agar tidak 'nabrak' */
        .page-wrap.is-inside-layout {
            padding-left: 24px;
            padding-right: 24px;
        }
    </style>
</head>

<body>
    <div class="page-wrap">
        <div class="register-container">

            <div class="register-box">
                <!-- logo -->
                <img src="../../../storages/navbar/goticket.png" class="logo-goticket" alt="Goticket Logo">

                <h1>Daftar Akun</h1>
                <p class="lead">Buat akun Penumpang untuk memesan tiket di Goticket</p>

                <form action="../../actions/auth/register_action.php" method="POST" autocomplete="off" novalidate>

                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" required maxlength="50" placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" required maxlength="25" placeholder="username">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required maxlength="50" placeholder="email@contoh.com">
                    </div>

                    <div class="form-group">
                        <label for="telepon">No. Telepon</label>
                        <input id="telepon" type="text" name="telepon" required maxlength="15" placeholder="0812xxxx">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required maxlength="32" placeholder="Masukkan password">
                    </div>

                    <button type="submit" class="register-btn">Daftar</button>
                </form>

                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
