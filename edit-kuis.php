<?php
include "koneksi.php";
include "login_check.php";


$id_kuis = isset($_GET['id_kuis']) ? intval($_GET['id_kuis']) : 0;
$sql_kuis = "SELECT * FROM kuis WHERE id_kuis = $id_kuis";
$result_kuis = $conn->query($sql_kuis);
if (!$result_kuis->num_rows) {
    echo "<script>alert('Kuis tidak ditemukan.'); window.location.href = './kuis_saya.php';</script>";
    exit;
}
$kuis_data = $result_kuis->fetch_assoc();

if ($kuis_data['id_pembuat'] != $_SESSION['id_user']) {
    echo "<script>alert('Anda tidak memiliki akses untuk mengedit kuis ini.'); window.location.href = './kuis_saya.php';</script>";
    exit;
}

// Fetch existing questions and answers
$sql_soal = "SELECT * FROM soal_kuis WHERE id_kuis = $id_kuis";
$result_soal = $conn->query($sql_soal);
$soal_data = [];
while ($soal = $result_soal->fetch_assoc()) {
    $sql_jawaban = "SELECT * FROM jawaban_soal WHERE id_soal = ".$soal['id_soal'];
    $result_jawaban = $conn->query($sql_jawaban);
    $jawaban_data = [];
    while ($jawaban = $result_jawaban->fetch_assoc()) {
        $jawaban_data[] = $jawaban;
    }
    $soal['jawaban'] = $jawaban_data;
    $soal_data[] = $soal;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_kuis = $_POST['judul_kuis'];
    
    // Handle thumbnail upload or keep existing
    $thumbnail_kuis = (isset($_FILES['thumbnail_kuis']) && $_FILES['thumbnail_kuis']['error'] === UPLOAD_ERR_OK) 
        ? $_FILES['thumbnail_kuis'] 
        : ['name' => $kuis_data['thumbnail'], 'error' => UPLOAD_ERR_NO_FILE, 'type' => ''];

    // Validation checks (similar to create page)
    if (!isset($_POST['judul_kuis']) || empty(trim($_POST['judul_kuis']))) {
        echo "<script>alert('Judul kuis tidak boleh kosong.'); window.location.href = './edit-kuis.php?id_kuis=$id_kuis';</script>";
        exit;
    }

    $sql_update_kuis = "UPDATE kuis SET judul = '$judul_kuis', thumbnail = '{$thumbnail_kuis['name']}' WHERE id_kuis = $id_kuis";
    if ($conn->query($sql_update_kuis)) {
        if (isset($thumbnail_kuis) && $thumbnail_kuis['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($thumbnail_kuis['type'], $allowed_types)) {
                move_uploaded_file($thumbnail_kuis['tmp_name'], "uploads/" . $thumbnail_kuis['name']);
            } else {
                echo "<script>alert('Tipe file thumbnail tidak valid. Hanya JPG, PNG, dan GIF yang diperbolehkan.'); window.location.href = './edit-kuis.php?id_kuis=$id_kuis';</script>";
                exit;
            }
        }

        $sql_delete_jawaban = "DELETE j FROM jawaban_soal j 
            INNER JOIN soal_kuis s ON j.id_soal = s.id_soal 
            WHERE s.id_kuis = $id_kuis";
        $conn->query($sql_delete_jawaban);

        $sql_delete_soal = "DELETE FROM soal_kuis WHERE id_kuis = $id_kuis";
        $conn->query($sql_delete_soal);

        if (isset($_POST['soal'])) {
            foreach ($_POST['soal'] as $index => $konten_soal) {
                $sql_insert_soal = "INSERT INTO soal_kuis (id_kuis, konten) VALUES ($id_kuis, '$konten_soal')";
                if ($conn->query($sql_insert_soal)) {
                    $id_soal = $conn->insert_id;
                    if (isset($_POST['jawaban'][$index])) {
                        foreach ($_POST['jawaban'][$index] as $j_index => $konten_jawaban) {
                            $is_correct = isset($_POST['correct_answer'][$index]) && 
                                        in_array($j_index, $_POST['correct_answer'][$index]) ? 1 : 0;
                            $sql_insert_jawaban = "INSERT INTO jawaban_soal (id_soal, konten, jawaban_benar) 
                                                 VALUES ($id_soal, '$konten_jawaban', $is_correct)";
                            $conn->query($sql_insert_jawaban);
                        }
                    }
                }
            }
        }
        echo "<script>alert('Kuis berhasil diperbarui!'); window.location.href = './kuis_saya.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quis - Edit Kuis <? echo htmlspecialchars($kuis_data['judul']); ?></title>
    <link rel="stylesheet" href="./style/style.css">
    <title>Quis - Edit Kuis <?php echo htmlspecialchars($kuis_data['judul']); ?></title>

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
            <h2 class="content-title">Edit Kuis</h2>
            <hr class="hr-main">
            <form action="?id_kuis=<?php echo $id_kuis; ?>" method="post" class="flex-column" enctype="multipart/form-data">
                <label for="judul_kuis">Judul Kuis: </label>
                <input type="text" name="judul_kuis" id="input_judul_kuis" class="input-text" 
                       style="max-width: 240px;" required value="<?php echo htmlspecialchars($kuis_data['judul']); ?>">

                <label for="thumbnail_kuis">Thumbnail Kuis: </label>
                <input type="file" accept="image/*" name="thumbnail_kuis" id="input_thumbnail_kuis" class="input-text">
                <small>Current thumbnail: <?php echo htmlspecialchars($kuis_data['thumbnail']); ?></small>

                <br>

                <div id="soal-container" style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($soal_data as $index => $soal): ?>
                    <div class="soal-group">
                        <!-- Pre-fill each question and its answers -->
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label for="soal">Soal <?php echo $index + 1; ?>:</label>
                            <button type="button" onclick="hapusSoal(this)" class="btn-danger">Hapus Soal</button>
                        </div>
                        <textarea name="soal[]" class="input-text" rows="4" required><?php echo htmlspecialchars($soal['konten']); ?></textarea>

                        <div class="jawaban-container" style="display: flex; flex-direction: column; gap: 4px;">
                            <h4>Jawaban:</h4>
                            <?php foreach ($soal['jawaban'] as $j_index => $jawaban): ?>
                            <div class="jawaban-input" style="max-width: 500px; display: flex; align-items: center; gap: 10px;">
                                <input type="text" name="jawaban[<?php echo $index; ?>][]" class="input-text" 
                                       required value="<?php echo htmlspecialchars($jawaban['konten']); ?>">
                                <label style="display: flex; gap: 4px;">
                                    <input type="checkbox" name="correct_answer[<?php echo $index; ?>][]" 
                                           value="<?php echo $j_index; ?>" <?php echo $jawaban['jawaban_benar'] ? 'checked' : ''; ?>>Benar
                                </label>
                                <button type="button" onclick="hapusJawaban(this)" class="btn-danger">Hapus</button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" onclick="tambahJawaban(<?php echo $index?>)" class="btn-secondary">Tambah Jawaban</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" onclick="tambahSoal(<?php echo count($soal_data)?>)" class="btn-secondary">Tambah Soal</button>

                <script>
                    let soalCount = <?php echo count($soal_data); ?>;
                    let jawabanCount = {};

                    function tambahSoal() {
                        const container = document.getElementById('soal-container');
                        const div = document.createElement('div');
                        div.className = 'soal-group';
                        jawabanCount[soalCount] = 1;

                        div.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label for="soal">Soal ${soalCount + 1}:</label>
                            <button type="button" onclick="hapusSoal(this)" class="btn-danger">Hapus Soal</button>
                        </div>
                        <textarea name="soal[]" class="input-text" rows="4" required></textarea>

                        <div class="jawaban-container" style="display: flex; flex-direction: column; gap: 4px;">
                            <h4>Jawaban:</h4>
                            <div class="jawaban-input">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="text" name="jawaban[${soalCount}][]" class="input-text" required>
                                    <label style="display: flex; gap: 4px;"><input type="checkbox"
                                            name="correct_answer[${soalCount}][]" value="0">Benar</label>
                                    <button type="button" onclick="hapusJawaban(this)" class="btn-danger">Hapus</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="tambahJawaban(${soalCount})" class="btn-secondary">Tambah Jawaban</button>
                    `;
                        container.appendChild(div);
                        soalCount++;
                    }

                    function tambahJawaban(soalIndex) {
                        if (!jawabanCount[soalIndex]) {
                            jawabanCount[soalIndex] = 1;
                        }

                        const container = document.querySelector(`#soal-container .soal-group:nth-child(${soalIndex + 1}) .jawaban-container`);
                        const div = document.createElement('div');
                        div.className = 'jawaban-input';
                        div.style.maxWidth = '500px';
                        div.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="text" name="jawaban[${soalCount}][]" class="input-text" required>
                                    <label style="display: flex; gap: 4px;"><input type="checkbox"
                                            name="correct_answer[${soalCount}][]" value="0">Benar</label>
                                    <button type="button" onclick="hapusJawaban(this)" class="btn-danger">Hapus</button>
                                </div>
                    `;
                        container.appendChild(div);
                        jawabanCount[soalIndex]++;
                    }

                    function hapusSoal(button) {
                        button.closest('.soal-group').remove();
                        // Update soal numbers
                        document.querySelectorAll('.soal-group').forEach((soal, index) => {
                            soal.querySelector('label').textContent = `Soal ${index + 1}:`;
                        });
                        soalCount--;
                    }

                    function hapusJawaban(button) {
                        button.closest('.jawaban-input').remove();
                    }
                </script>

                <button type="submit">Simpan Perubahan</button>
            </form>
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
