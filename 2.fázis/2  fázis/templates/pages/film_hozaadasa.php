<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limitáljuk a mezőket
    $rendezo_limit = 100;
    $cim_limit = 100;
    $leiras_limit = 1000;
    $szereplok_limit = 500;

    $cim = $_POST['cim'];
    $rendezo = $_POST['rendezo'];
    $megjelenes_datum = $_POST['megjelenes_datum'];
    $leiras = $_POST['leiras'];
    $szereplok = $_POST['szereplok'];

    // Ellenőrizzük, hogy minden szükséges mező ki van-e töltve
    if (empty($cim) || empty($rendezo) || empty($szereplok) || empty($megjelenes_datum) || empty($leiras) || empty($_FILES['borito_kep']['name'])) {
        echo "<div class='message error'>Kérlek, tölts ki minden mezőt!</div>";
    } else {
        // Ellenőrizzük, hogy nem lépték-e túl a karakterkorlátot
        if (strlen($cim) > $cim_limit || strlen($rendezo) > $rendezo_limit || strlen($leiras) > $leiras_limit || strlen($szereplok) > $szereplok_limit) {
            echo "<div class='message error'>Valamelyik mező túl hosszú! A cím, rendező maximum 100 karakter, a leírás maximum 1000 karakter, a szereplők maximum 500 karakter lehet.</div>";
        } else {
            // Fájl feltöltése
            $borito_kep_tmp = $_FILES['borito_kep']['tmp_name'];
            $borito_kep = 'pictures/' . $_FILES['borito_kep']['name'];

            if (move_uploaded_file($borito_kep_tmp, $borito_kep)) {
                // Adatbázisba való mentés
                $stmt = $conn->prepare("INSERT INTO filmek (cim, rendezo, szereplok, megjelenes_datum, leiras, borito_kep) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $cim, $rendezo, $szereplok, $megjelenes_datum, $leiras, $borito_kep);

                if ($stmt->execute()) {
                    // Sikeresen beszúrva az adatbázisba
                    echo "<div class='message success'>A film sikeresen hozzáadva az adatbázishoz.</div>";
                } else {
                    // Hiba az adatbázisba való beszúrás közben
                    echo "<div class='message error'>Hiba történt az adatbázisba való beszúrás során.</div>";
                }
                $stmt->close();
            } else {
                // Hiba a fájl feltöltése közben
                echo "<div class='message error'>Hiba történt a fájl feltöltése közben.</div>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <link rel="stylesheet" href="style.css"> 
    <title>Film hozzáadása</title>
    <style>
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


.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #7c7fdd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
}

form {
    margin-bottom: 20px;
}

input[type="text"],
textarea,
input[type="file"] {
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

a {
    color: #fff;
    text-decoration: none;
    margin-right: 20px;
}

/* Üzenetek stílusai */
.message {
    padding: 10px 20px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.my-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6dc2d1;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .my-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Film hozzáadása</h1>
        <form action="index.php?oldal=film_hozaadasa" method="post" enctype="multipart/form-data">
            Cím: <input type="text" name="cim"><br>
            Rendező: <input type="text" name="rendezo"><br>
            Szereplők: <input type="text" name="szereplok"><br>
            Megjelenési dátum: <input type="date" name="megjelenes_datum"><br>
            Leírás: <textarea name="leiras"></textarea><br>
            Borító kép: <input type="file" name="borito_kep"><br>
            <input type="submit" value="Feltöltés" name="submit" style="background-color: green; color: white;">
        </form>
        <a href="index.php?oldal=film_lista" class="my-button">Film Lista</a>
        <a href="index.php" class="my-button">Vissza a főoldalra</a>
    </div>
</body>
</html>