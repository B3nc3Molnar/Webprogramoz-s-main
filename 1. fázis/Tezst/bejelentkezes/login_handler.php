<?php
session_start();
require_once '../config.php';

// Ellenőrizzük, hogy az űrlapot elküldték-e
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ellenőrizzük a felhasználónév és jelszó helyességét
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ellenőrizzük az adatbázisban
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Sikeres bejelentkezés, beállítjuk a session változókat
            $_SESSION['user_id'] = $row['id']; // Felhasználó azonosítója
            $_SESSION['username'] = $username; // Felhasználó neve
            header('Location: ../fooldal/index.php');
            exit();
        } else {
            // Hibás jelszó
            echo '<div style="background-color: #ffcccc; padding: 10px;">Hibás felhasználónév vagy jelszó!</div>';
        }
    } else {
        // Hibás felhasználónév
        echo '<div style="background-color: #ffcccc; padding: 10px;">Hibás felhasználónév vagy jelszó!</div>';
    }

    $stmt->close();
} else {
    // Ha nem POST kérés érkezett, valami nincs rendben
    echo '<div style="background-color: #ffcccc; padding: 10px;">Hiba történt a bejelentkezés során.</div>';
}

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <style>
        body {
            background-image: url('hatter.jpg'); /* Háttérkép beállítása */
            background-size: cover; /* Háttérkép méretének beállítása */
            background-position: center; /* Háttérkép pozíciójának beállítása */
            font-family: Arial, sans-serif; /* Betűtípus beállítása */
        }

        /* Stílus a gombhoz */
        .back-button {
            background-color: #e01710; /* Háttérszín */
            color: white; /* Betűszín */
            border: none; /* Keret nélkül */
            padding: 10px 20px; /* Belső térköz */
            border-radius: 4px; /* Kerekített sarok */
            cursor: pointer; /* Mutató ikon a hover felett */
            text-decoration: none; /* Betű alatti vonal eltávolítása */
            display: inline-block; /* Blokk elem */
            margin-top: 20px; /* Felső margó */
        }

        .back-button:hover {
            background-color: #0056b3; /* Háttérszín a hover felett */
        }
    </style>
</head>

<body>
    <div style="text-align: center;">
        <!-- Egy egyszerű vissza gomb a login.php-ra -->
        <a href="login.php" class="back-button">Vissza a bejelentkezéshez</a>
    </div>
</body>

</html>
