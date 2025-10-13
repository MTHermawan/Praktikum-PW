<?php session_start();
include "koneksi.php";

$submitted = $_SERVER['REQUEST_METHOD'] == 'POST';
$isNameAvailable = true;

if ($submitted) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($sql);
    $isNameAvailable = $result->num_rows < 1;

    if ($isNameAvailable) {
        $sql = "INSERT INTO user(username, password) VALUES ('$username', '$password')";
        $result = $conn->query($sql);

        if ($result)
            echo "<script>alert('Registrasi berhasil!'); window.location.href='login.php'</script>";
        else
            echo "<script>alert('Registrasi gagal!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quis - Registrasi</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <!-- <header>
        <h1><em>QUIS</em></h1>
    </header>
    <hr> -->
    <main class="flex-center">
        <article class="card flex-center flex-column">
            <h2>Registrasi</h2>
            <form action="" method="POST" class="flex-column">
                <label for="username">Username: </label>
                <input type="text" name="username" id="input_username" class="input-text">

                <label for="password">Password: </label>
                <input type="password" name="password" id="input_password" class="input-text">

                <?php
                if ($submitted && !$isNameAvailable)
                    echo '<p class="font-red hidden" id="invalid-login-message">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>';
                else
                    echo "<p class='font-red' id='invalid-login-message'>$message</p>";
                ?>

                <button type="submit">Registrasi</button>
                <p class="font-grey">Sudah punya akun? Silakan melakukan <a href="login.php">Log In!</a></p>
            </form>
        </article>
    </main>
</body>

<script src="./script/login_auth.js"></script>

</html>