<?php
include "koneksi.php";
include "login_check.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_kuis = $_POST['judul_kuis'];
    if (isset($_FILES['thumbnail_kuis']) && $_FILES['thumbnail_kuis']['error'] === UPLOAD_ERR_OK) {
        $thumbnail_kuis = $_FILES['thumbnail_kuis'];
    } else {
        $thumbnail_kuis = ['name' => '']; // Set a default thumbnail name
    }

    if (!isset($_POST['judul_kuis']) || empty(trim($_POST['judul_kuis']))) {
        echo "<script>alert('Judul kuis tidak boleh kosong.'); window.location.href = './buat_kuis.php';</script>";
        exit;
    }
    $soal_array = $_POST['soal'];
    
    if (!isset($_POST['jawaban']) || empty($_POST['jawaban'])) {
        echo "<script>alert('Setidaknya satu jawaban harus ditambahkan untuk setiap soal.'); window.location.href = './buat_kuis.php';</script>";
        exit;
    }
    $jawaban_array = $_POST['jawaban'];

    if (!isset($_POST['correct_answer'])) {
        echo "<script>alert('Setidaknya satu jawaban benar harus dipilih untuk setiap soal.'); window.location.href = './buat_kuis.php';</script>";
        exit;
    }
    $correct_answer_array = $_POST['correct_answer'];

    $sql_kuis = "INSERT INTO kuis (judul, thumbnail, id_pembuat) VALUES ('$judul_kuis', '{$thumbnail_kuis['name']}', {$_SESSION['id_user']})";
    if ($conn->query($sql_kuis) == true) {
        $id_kuis = $conn->insert_id;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (isset($thumbnail_kuis) && $thumbnail_kuis['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($thumbnail_kuis['type'], $allowed_types)) {
                move_uploaded_file($thumbnail_kuis['tmp_name'], "uploads/" . $thumbnail_kuis['name']);
            } else {
                echo "<script>alert('Tipe file thumbnail tidak valid. Hanya JPG, PNG, dan GIF yang diperbolehkan.'); window.location.href = './buat_kuis.php';</script>";
                exit;
            }
        }

        foreach ($soal_array as $index => $soal) {
            $sql_soal = "INSERT INTO soal_kuis (id_kuis, konten) VALUES ($id_kuis, '$soal')";
            if ($conn->query($sql_soal) == true) {
                $id_soal = $conn->insert_id;

                foreach ($jawaban_array[$index] as $j_index => $jawaban) {
                    $jawaban_benar = in_array($j_index, array_map('intval', $correct_answer_array[$index])) ? 1 : 0;
                    $sql_jawaban = "INSERT INTO jawaban_soal (id_soal, konten, jawaban_benar) VALUES ($id_soal, '$jawaban', $jawaban_benar)";
                    if ($conn->query($sql_jawaban) !== true) {
                        echo "Error: " . $sql_jawaban . "<br>" . $conn->error;
                    }
                }
            } else {
                echo "Error: " . $sql_soal . "<br>" . $conn->error;
            }
        }

        echo "<script>alert('Kuis berhasil dibuat!'); window.location.href = './kuis_saya.php';</script>";
    } else {
        echo "Error: " . $sql_kuis . "<br>" . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quis - Buat Kuis</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <header>
        <div>
            <h1><em><a href="./index.php" class="text-color-main">QU!S</a></em></h1>
            <nav>
                <a href="./index.php" class="alt-nav-button">Beranda</a>
                <a href="./buat_kuis.php" class="nav-button">Buat Kuis</a>
            </nav>
        </div>
        <div>
            <nav>
                <!-- <a href="./index.php" class="nav-button">Daftar Kuis</a> -->
                <a href="./login.php" class="nav-button">Log In</a>
                <a href="./logout.php" class="alt-nav-button">Log Out</a>
            </nav>
        </div>
    </header>
    <main style="min-height: 100vh; padding-bottom: 2rem; height: fit-content;">
        <section id="daftar_kuis" class="content-container">
            <h2 class="content-title">Buat Kuis</h2>
            <hr class="hr-main">
            <form action="" method="post" class="flex-column" enctype="multipart/form-data">
                <label for="judul_kuis">Judul Kuis: </label>
                <input type="text" name="judul_kuis" id="input_judul_kuis" class="input-text" style="max-width: 240px;" required>

                <label for="thumbnail_kuis">Thumbnail Kuis: </label>
                <input type="file" accept="image/*" name="thumbnail_kuis" id="input_thumbnail_kuis" class="input-text">

                <br>

                <div id="soal-container" style="display: flex; flex-direction: column; gap: 1rem;">
                    <div class="soal-group">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label for="soal">Soal 1:</label>
                            <button type="button" onclick="hapusSoal(this)" class="btn-danger">Hapus Soal</button>
                        </div>
                        <textarea name="soal[]" class="input-text" rows="4" required></textarea>

                        <div class="jawaban-container" style="display: flex; flex-direction: column; gap: 4px;">
                            <h4>Jawaban:</h4>
                            <div class="jawaban-input" style="max-width: 500px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="text" name="jawaban[0][]" class="input-text" required>
                                    <label style="display: flex; gap: 4px;"><input type="checkbox"
                                            name="correct_answer[0][]" value="0">Benar</label>
                                    <button type="button" onclick="hapusJawaban(this)" class="btn-danger">Hapus</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="tambahJawaban(0, 0)" class="btn-secondary">Tambah Jawaban</button>
                    </div>
                </div>
                <button type="button" onclick="tambahSoal(0)" class="btn-secondary">Tambah Soal</button>
                <script src="./script/crud_kuis.js"></script>

                <button type="submit">Buat Kuis</button>
        </section>
    </main>
    <footer>
        <div class="column">
            <p>© 2025 Quis - MTHermawan</p>
        </div>
        <div class="column">
            <p>Referensi:</p>
            <a href="https://kahoot.com" target="_blank">Kahoot: kahoot.com</a>
            <a href="https://wayground.com/admin" target="_blank">Wayground: wayground.com</a>
        </div>
        <div class="column">
            <p>Kontak:</p>
            <p>GitHub: <a href="https://github.com/MTHermawan/">MTHermawan</a></p>
        </div>
    </footer>
</body>

</html>