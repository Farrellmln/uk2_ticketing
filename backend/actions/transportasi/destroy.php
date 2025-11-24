<?php
include '../../app.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q = "DELETE FROM transportasi WHERE id_transportasi = '$id'";
    $res = mysqli_query($connect, $q);

    if ($res) {
        echo "
            <script>
                alert('Data transportasi berhasil dihapus!');
                window.location.href = '../../pages/transportasi/index.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Gagal menghapus data transportasi!');
                window.history.back();
            </script>
        ";
    }
} else {
    echo "
        <script>
            alert('Akses tidak valid!');
            window.location.href = '../../pages/transportasi/index.php';
        </script>
    ";
}
?>
