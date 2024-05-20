<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['username'])) {
    header("Location: index.php?oldal=login");
    exit();
}

if (isset($_POST['delete'])) {
    $username = $_SESSION['username'];

    // SQL lekérdezés a felhasználói fiók törlésére
    $stmt_delete_user = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt_delete_user->bind_param("s", $username);

    // SQL lekérdezés a felhasználóhoz kapcsolódó feltöltött képek törlésére
    $stmt_delete_images = $conn->prepare("DELETE FROM uploaded_images WHERE uploaded_by = ?");
    $stmt_delete_images->bind_param("s", $username);

    // SQL lekérdezés a felhasználóhoz kapcsolódó film értékelések törlésére
    $stmt_delete_ratings = $conn->prepare("DELETE FROM film_ertekeles WHERE felhasznalo = ?");
    $stmt_delete_ratings->bind_param("s", $username);

    // SQL lekérdezés a felhasználóhoz kapcsolódó üzenetek törlésére
    $stmt_delete_messages = $conn->prepare("DELETE FROM messages WHERE username = ?");
    $stmt_delete_messages->bind_param("s", $username);

    // Tranzakció kezdése
    $conn->begin_transaction();

    // Felhasználó törlése
    if ($stmt_delete_user->execute()) {
        // Feltöltött képek törlése
        $stmt_delete_images->execute();
        // Film értékelések törlése
        $stmt_delete_ratings->execute();
        // Üzenetek törlése
        $stmt_delete_messages->execute();

        // Sikeres törlés után commit
        $conn->commit();

        // Töröljük a session változókat és irányítsunk át a kijelentkezési oldalra
        session_unset();
        session_destroy();
        header("Location: logicals/logout.php");
        exit();
    } else {
        // Hiba az adatbázisba való törlés közben
        echo "Hiba történt a fiók törlése során.";
        // Tranzakció visszagörgetése hiba esetén
        $conn->rollback();
    }

    // Statementek bezárása
    $stmt_delete_user->close();
    $stmt_delete_images->close();
    $stmt_delete_ratings->close();
    $stmt_delete_messages->close();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <title>Bejelentkezés</title>
    <!-- CSS fájlok importálása -->
    <link rel="stylesheet" href="style.css">
    <title>Fiók törlése</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('pictures/hatter.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #c20606;
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
    text-decoration: none; /* eltávolítjuk az alapértelmezett link aláhúzást */
}

.my-button:hover {
    background-color: #0056b3;
}

        </style>
</head>
<body>
    <div class ="container">

<h2>Biztosan törölni szeretné a fiókját?</h2>
<form method="post">
    <button class="my-button" type="submit" name="delete">Fiók törlése</button>
    <a href="index.php" class="my-button">Vissza a főoldalra</a>
</form>
    </div>
</body>
</html>
