<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');
if (isset($_SESSION['username'])) {
    echo '<div style="background-color: rgba(255, 0, 0, 0.5); padding: 20px; color: white; text-align: center; font-size: 24px;">Már be vagy regisztrálva!<br><br><a href="index.php" style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Vissza a főoldalra</a></div>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Felhasználótól kapott adatok feldolgozása
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ellenőrizzük, hogy a felhasználónév és az e-mail egyediek legyenek
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        // Ha a felhasználónév vagy az e-mail már foglalt, hibaüzenet
        echo "A felhasználónév vagy az e-mail már foglalt.";
        exit();
    }

    // Jelszó hashelése
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Felhasználó létrehozása
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        // Sikeres regisztráció után irányítás a bejelentkezési oldalra
        header("Location: index.php?oldal=login");
        exit();
    } else {
        echo "Hiba történt a regisztráció során.";
    }

    $stmt->close();
}
?>




<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <!-- CSS fájlok importálása -->
    <link rel="stylesheet" href="style.css">
    <!-- JavaScript fájlok importálása -->
    <script src="script.js" defer></script>
    <title>Regisztráció</title>
    <style>
        

/* Regisztrációs űrlap */
.content {
    margin: 50px auto;
    width: 300px;
    padding: 20px;
    background-color: #12e1d0;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.content h2 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    text-align: center;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input[type="text"],
form input[type="email"],
form input[type="password"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}


form input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

/* style.css */

/* Alap stílusok */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url('pictures/hatter.jpg');
    background-size: auto;
    background-repeat: repeat;
    color: #fff;
}

/* Fejléc */
.header {
    background-color: rgba(0, 0, 0, 0.7);
    text-align: center;
    padding: 10px;
}

.header h1 {
    color: #fff;
    margin: 0;
    font-size: 24px;
}

/* Menü */
nav {
    text-align: center;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 10px;
}

nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

nav ul li {
    display: inline;
}

nav ul li a {
    color: white;
    text-decoration: none;
    padding: 10px;
}

nav ul li a:hover {
    background-color: rgba(0, 0, 0, 0.7);
}



/* Címkék középre igazítása */

h1 {
    color: #000000;
    font-size: 45px;
    margin-bottom: 10px;
    text-align: center;
}

h2 {
    color: #000000;
    font-size: 35px;
    margin-bottom: 10px;
    text-align: center;
}

h3 {
    color: #000000;
    font-size: 20px;
    margin-bottom: 10px;
    text-align: left;
}

/* Címsor háttérsáv */
.hero {
    background-color: rgba(166, 21, 21, 0.2);
    font-size: 20px;
    margin-bottom: 10px;
    padding: 20px;
}
    </style>
</head>
<body>
    <div class="content">
    <h1>Regisztráció</h1>
    <form action="index.php?oldal=register" method="post">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">E-mail cím:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="register">Regisztráció</button>
        </form>
</div>
</body>
</html>
