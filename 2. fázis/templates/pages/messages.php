<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    // Ha a felhasználó nincs bejelentkezve, átirányítjuk a bejelentkezési oldalra
    header("Location: index.php?oldal=login");
    exit();
}

// Felhasználó neve
$username = $_SESSION['username'];

// Ellenőrizzük, hogy van-e üzenet az URL-ben és az üzenet létezik-e
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Töröljük az üzenetet az adatbázisból
    $stmt_delete = $conn->prepare("DELETE FROM messages WHERE id = ? AND username = ?");
    $stmt_delete->bind_param("is", $delete_id, $username);
    if ($stmt_delete->execute()) {
        // Sikeres törlés után frissítjük az oldalt
        header("Location: index.php?oldal=messages");
        exit();
    } else {
        echo "Hiba történt az üzenet törlése során.";
    }
}

// Lekérdezzük az előző üzeneteket az adatbázisból rendezve dátum szerint csökkenő sorrendben
$stmt = $conn->prepare("SELECT * FROM messages WHERE username = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <link rel="stylesheet" href="style.css">
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
    <title>Üzenetek</title>
</head>
<body>
    <div class="container">
        <h1>Üzenetek</h1>
        <p>Itt láthatóak az üzeneteid:</p>
        <ul>
            <?php
            // Ellenőrizzük, hogy vannak-e üzenetek
            if ($result->num_rows > 0) {
                // Megjelenítjük az előző üzeneteket
                while ($row = $result->fetch_assoc()) {
                    echo "<p><strong>Üzenet:</strong> " . $row['message'] . "</p>";
                    echo "<p><strong>Dátum:</strong> " . $row['created_at'] . "</p>";
                    // Törlés gomb
                    echo "<a href='index.php?oldal=messages&delete_id=" . $row['id'] . "'>Törlés</a>";
                    echo "<hr>";
                }
            } else {
                echo '<div style="text-align: center;">';
                echo "Nincsenek korábbi üzeneteid.";
                echo "</div>";
            }

            $stmt->close();
            ?>
        </ul>
        <p>Ugrás a Főoldalra <a href="index.php">Főoldal</a>.</p>
    </div>
</body>
</html>
