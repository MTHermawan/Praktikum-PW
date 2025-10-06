<?php session_start();

if (isset($_GET['username']) && isset($_GET['password']))
{
    $username = htmlspecialchars($_GET['username']);
    $password = htmlspecialchars($_GET['password']);

    if ($username != '' && $password != '')
    {
        header('location: login.php');
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
            <form action="" method="get" class="flex-column">
                <label for="username">Email: </label>
                <input type="text" name="username" id="input_username" class="input-text">

                <label for="password">Password: </label>
                <input type="password" name="password" id="input_password" class="input-text">
                
                <p class="font-red hidden" id="invalid-login-message">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            
                <button type="submit">Login</button>
                <p class="font-grey">Sudah punya akun? Silakan melakukan <a href="login.php">Log In!</a></p>
            </form>
        </article>
    </main>
</body>

<script src="./script/login_auth.js"></script>
</html>