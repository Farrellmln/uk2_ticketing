<?php
include '../../app.php';

$transportasiList = mysqli_query($connect, "SELECT * FROM transportasi");

while ($t = mysqli_fetch_assoc($transportasiList)) {

    $id = $t['id_transportasi'];
    $jenis = $t['jenis'];
    $kapasitas = (int)$t['kapasitas'];

    echo "Generate untuk transportasi ID $id ($jenis)...<br>";

    // hapus kursi lama (re-generate)
    mysqli_query($connect, "DELETE FROM kursi WHERE id_transportasi = $id");

    if ($jenis == "Pesawat") {
        // 3–4–3 layout, baris 1 - 30
        $cols = ['A','B','C','D','E','F','G','H','I','J'];
        $rows = 30;

        for ($r = 1; $r <= $rows; $r++) {
            foreach ($cols as $c) {
                $nomor = $r . $c;
                mysqli_query($connect,
                    "INSERT INTO kursi (id_transportasi, nomor_kursi, status_kursi)
                     VALUES ($id, '$nomor', 'kosong')"
                );
            }
        }

    } else {
        // Kereta 2–2 layout
        $cols = ['A','B','C','D'];
        $rows = ceil($kapasitas / 4);

        for ($r = 1; $r <= $rows; $r++) {
            foreach ($cols as $c) {
                $nomor = $r . $c;
                mysqli_query($connect,
                    "INSERT INTO kursi (id_transportasi, nomor_kursi, status_kursi)
                     VALUES ($id, '$nomor', 'kosong')"
                );
            }
        }
    }
}

echo "<br><b>SELESAI GENERATE KURSI!</b>";
?>
