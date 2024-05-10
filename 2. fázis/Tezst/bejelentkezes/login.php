<?php
session_start();
if (isset($_SESSION['username'])) {
    echo '<div style="background-color: rgba(255, 0, 0, 0.5); padding: 20px; color: white; text-align: center; font-size: 24px;">Már be vagy jelentkezve!<br><br><a href="../fooldal/index.php" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Vissza a főoldalra</a></div>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icon.jpg">
    <title>Bejelentkezés</title>
    <style>
        body {
            background-image: url('hatter.jpg'); /* Háttérkép beállítása */
            background-size: cover; /* Háttérkép méretének beállítása */
            background-position: center; /* Háttérkép pozíciójának beállítása */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Átlátszó háttér */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #007bff;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .my-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none; /* Betű alatti vonal eltávolítása */
        }

        .my-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Bejelentkezés</h1>
        <form action="login_handler.php" method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username">
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="Bejelentkezés">
        </form>
        <br>
        <a href="../fooldal/index.php" class="my-button">Vissza a főoldalra</a>
    </div>
</body>
</html>
