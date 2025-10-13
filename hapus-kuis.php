<?php
include 'koneksi.php';
include 'login_check.php';

if (isset($_GET['id_kuis'])) {
    $id_kuis = $_GET['id_kuis'];

    // Hapus soal dan jawaban terkait
    $sql_soal = "SELECT id_soal FROM soal_kuis WHERE id_kuis = $id_kuis";
    $result_soal = $conn->query($sql_soal);
    if ($result_soal->num_rows > 0) {
        while ($row_soal = $result_soal->fetch_assoc()) {
            $id_soal = $row_soal['id_soal'];
            $sql_hapus_jawaban = "DELETE FROM jawaban_soal WHERE id_soal = $id_soal";
            $conn->query($sql_hapus_jawaban);
        }
    }
    $sql_hapus_soal = "DELETE FROM soal_kuis WHERE id_kuis = $id_kuis";
    $conn->query($sql_hapus_soal);

    // Hapus kuis
    $sql_hapus_kuis = "DELETE FROM kuis WHERE id_kuis = $id_kuis AND id_pembuat = " . $_SESSION['id_user'];
    if ($conn->query($sql_hapus_kuis) === TRUE) {
        echo "<script>alert('Kuis berhasil dihapus.'); window.location.href = './kuis_saya.php';</script>";
    } else {
        echo "<script>alert('Error menghapus kuis: " . $conn->error . "'); window.location.href = './kuis_saya.php';</script>";
    }
} else {
    echo "<script>alert('ID kuis tidak ditemukan.'); window.location.href = './kuis_saya.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quis - Hapus Kuis</title>
</head>
<body>
</body>
</html>