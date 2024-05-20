<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy az űrlapot elküldték-e
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ellenőrizzük az űrlap adatokat
    if (!isset($_POST['message']) || empty($_POST['message'])) {
        echo 'Az üzenet mező kitöltése kötelező!';
        exit();
    }

    // Felhasználó neve
    if (isset($_SESSION['username'])) {
        // Ha be van jelentkezve, a felhasználó nevét használjuk
        $username = $_SESSION['username'];
    } else {
        // Ha nincs bejelentkezve, a vendég nevet használjuk
        $username = 'Vendég';
    }

    // Űrlap adatainak beolvasása
    $message = $_POST['message'];

    // Az űrlap adatainak tárolása az adatbázisban
    $stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $message);

    if ($stmt->execute()) {
        // Sikeres űrlapfeldolgozás esetén átirányítás a message.php oldalra
        header("Location: index.php?oldal=urlap");
        exit();
    } else {
        echo 'Hiba történt az üzenet beküldése során.';
    }

    $stmt->close();
} else {
    echo 'Hiba történt az űrlap feldolgozása során.';
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <title>Űrlap</title>
    <style>

body {
    background-image: url('pictures/hatter.jpg');
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-size: auto;
    background-repeat: repeat;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

form {
    text-align: left;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

p {
    text-align: center;
    margin-top: 20px;
}

a {
    text-decoration: none;
    color: #4CAF50;
}

a:hover {
    text-decoration: underline;
}

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
</style>
</head>
<body>
    <div class="container">
        <h1>Űrlap</h1>
        <form action="index.php?oldal=submit_form" method="post">
            <label for="name">Név:</label>
            <input type="text" id="name" name="name" required>

            <label for="message">Üzenet:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit" name="submit">Küldés</button>
        </form>

        <p>Ugrás a Főoldalra <a href="index.php">Főoldal</a>.</p>
    </div>
</body>
</html>
