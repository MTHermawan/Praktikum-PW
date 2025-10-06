<?php session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['username']))
    {
        {
            session_destroy();
            session_start();
            
            $_SESSION['username'] = $_POST['username'];
            header('location: ./index.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quis - Login</title>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body>
    <!-- <header>
        <h1><em>QUIS</em></h1>
    </header>
    <hr> -->
    <main class="flex-center">
        <article class="card flex-center flex-column">
            <h2>Login</h2>
            <form action="" method="post" class="flex-column">
                <label for="username">Email: </label>
                <input type="text" name="username" id="input_username" class="input-text">

                <label for="password">Password: </label>
                <input type="password" name="password" id="input_password" class="input-text">
                
                <p class="font-red hidden" id="invalid-login-message">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            
                <button type="submit">Login</button>
                <p class="font-grey">Belum punya akun? Silakan melakukan <a href="registrasi.php">Registrasi!</a></p>
            </form>
        </article>
    </main>
</body>

<script src="./script/login_auth.js"></script>
</html>