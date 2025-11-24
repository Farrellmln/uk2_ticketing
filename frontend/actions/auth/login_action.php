<?php
include '../../app.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // 🔹 Validasi input kosong
    if ($username === '' || $password === '') {
        echo "<script>
                alert('Username dan password wajib diisi!');
                window.location.href = '../../pages/auth/login.php';
              </script>";
        exit;
    }

    // 🔹 Cek di tabel user (karena semua role digabung)
    $stmt = $connect->prepare("SELECT id_user, nama_lengkap, username, password, role 
                               FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username ditemukan
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {

            // Simpan ke session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // 🔸 Arahkan berdasarkan role
            if ($user['role'] === 'Administrator') {
                echo "<script>
                        alert('Login berhasil sebagai Administrator!');
                        window.location.href = '../../../backend/pages/dashboard/index.php';
                      </script>";
                exit;
            } elseif ($user['role'] === 'Petugas') {
                echo "<script>
                        alert('Login berhasil sebagai Petugas!');
                        window.location.href = '../../../backend/pages/dashboard/index.php';
                      </script>";
                exit;
            } elseif ($user['role'] === 'Penumpang') {
                echo "<script>
                        alert('Login berhasil sebagai Penumpang!');
                        window.location.href = '../../../frontend/pages/utama/index.php';
                      </script>";
                exit;
            } else {
                echo "<script>
                        alert('Role tidak dikenali!');
                        window.location.href = '../../pages/auth/login.php';
                      </script>";
                exit;
            }
        } else {
            echo "<script>
                    alert('Password salah!');
                    window.location.href = '../../pages/auth/login.php';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('Username tidak ditemukan!');
                window.location.href = '../../pages/auth/login.php';
              </script>";
        exit;
    }
}
?>
