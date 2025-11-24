<?php
session_start();

if (isset($_SESSION['text'])) {
    echo "
      <script>
        alert('Anda harus logout dahulu');
        window.location.href = '../dashboard/index.php';
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
    <title>Login | Goticket</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="icon" href="../../../storages/navbar/g.png" type="image/png" />

    <style>
        :root {
            --goticket-blue: #004c84;
            --goticket-yellow: #f9b233;
        }

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg, #f4f8ff 0%, #e9f2ff 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 460px;
            padding: 20px;
        }

        .login-box {
            background: #fff;
            border-radius: 18px;
            padding: 36px 30px 38px;
            border-top: 6px solid var(--goticket-yellow);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            animation: fadeIn 0.45s ease;
            text-align: center;
        }

        /* 🔥 LOGO DIPERBESAR */
        .logo-goticket {
            width: 250px;   /* dari 120px → jadi 180px */
            height: auto;
            margin: 0 auto 15px;
            display: block;
        }

        h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: var(--goticket-blue);
        }

        p {
            margin-top: 6px;
            margin-bottom: 22px;
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            color: var(--goticket-blue);
        }

        input[type='text'],
        input[type='password'] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1.6px solid #cfd9e3;
            transition: 0.3s ease;
            font-size: 14px;
        }

        input:focus {
            border-color: var(--goticket-yellow);
            box-shadow: 0 0 6px rgba(249, 178, 51, 0.35);
            outline: none;
        }

        .sign-in-button {
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
            transition: 0.25s ease;
        }

        .sign-in-button:hover {
            background: #e19d24;
            transform: translateY(-2px);
        }

        .new-user {
            margin-top: 16px;
            font-size: 14px;
        }

        .new-user a {
            color: var(--goticket-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .new-user a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-box">

            <!-- 🔥 LOGO GOTICKET DIPERBESAR -->
            <img src="../../../storages/navbar/goticket.png" alt="Goticket Logo" class="logo-goticket">

            <h1>Login</h1>
            <p>Silakan masuk ke akun Goticket Anda</p>

            <form action="../../actions/auth/login_action.php" method="POST">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text"
                           id="username"
                           name="username"
                           maxlength="25"
                           placeholder="Masukkan username Anda"
                           required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password"
                           id="password"
                           name="password"
                           maxlength="32"
                           placeholder="Masukkan password"
                           required />
                </div>

                <button type="submit" class="sign-in-button">Masuk</button>
            </form>

            <div class="new-user">
                Belum punya akun? <a href="register.php">Buat akun</a>
            </div>

        </div>
    </div>
</body>

</html>
