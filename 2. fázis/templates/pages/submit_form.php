<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    echo 'Kérlek jelentkezz be, hogy beküldhesd az űrlapot!';
    exit();
}

// Ellenőrizzük, hogy az űrlapot elküldték-e
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ellenőrizzük az űrlap adatokat
    if (!isset($_POST['message']) || empty($_POST['message'])) {
        echo 'Az üzenet mező kitöltése kötelező!';
        exit();
    }

    // Felhasználó neve
    $username = $_SESSION['username'];

    // Űrlap adatainak beolvasása
    $message = $_POST['message'];

    // Az űrlap adatainak tárolása az adatbázisban
    $stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $message);

    if ($stmt->execute()) {
        // Sikeres űrlapfeldolgozás esetén átirányítás a message.php oldalra
        header("Location: index.php?oldal=messages");
        exit();
    } else {
        echo 'Hiba történt az üzenet beküldése során.';
    }

    $stmt->close();
} else {
    echo 'Hiba történt az űrlap feldolgozása során.';
}
?>
