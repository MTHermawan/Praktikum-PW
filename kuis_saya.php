<?php
include "koneksi.php";
include "login_check.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quis - Dashboard</title>
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
    <main style="height: fit-content; display: flex; flex-direction: column; gap: 24px;">
        <section id="kuis_saya" class="content-container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 class="content-title">Kuis Saya</h2>
                    <hr class="hr-main">
                </div>
                <a style="width: fit-content;" href="./kuis_saya.php" class="button">Lihat Semua</a>
            </div>
            <div class="card-container">
                <?php
                $sql = "SELECT * FROM kuis WHERE id_pembuat = " . $_SESSION['id_user'];
                $result = $conn->query($sql);
                if ($result->num_rows == 0) {
                    echo "<p class='font-grey'>Belum ada kuis yang tersedia.</p>";
                }
                while ($row = $result->fetch_assoc()) { ?>
                    <article class="card">
                        <h3><?php echo $row['judul']; ?></h3>
                        <?php if ($row['thumbnail'] && file_exists('./uploads/' . $row['thumbnail'])) { ?>
                            <img src="./uploads/<?php echo $row['thumbnail']; ?>" alt="Thumbnail Kuis">
                        <?php } ?>
                        <a href="edit-kuis.php?id_kuis=<?php echo $row['id_kuis'] ?>"><button class="button">Edit</button></a>
                        <a href="hapus-kuis.php?id_kuis=<?php echo $row['id_kuis'] ?>"><button class="button">Hapus</button></a>
                    </article>
                <?php } ?>
            </div>
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