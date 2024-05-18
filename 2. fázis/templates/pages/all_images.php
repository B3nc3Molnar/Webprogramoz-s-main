<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    header('Location: index.php?oldal=login');
    exit();
}

// Képek lekérdezése az adatbázisból
$sql = "SELECT * FROM uploaded_images ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Mappa, ahol a képek találhatók
$image_folder = "pictures/";

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <title>Összes kép megtekintése</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Általános formázás */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            background-image: url('pictures/hatter.jpg');
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #dbe03d;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }

        h1, h2, h3 {
            color: #333;
        }

        p {
            color: #000;
        }

        /* Galéria stílus */
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            grid-gap: 20px;
            color: #000;
        }

        .gallery img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.3);
            position: relative;
        }

        /* Stílus a jogosultsági hibaüzenethez */
        .error-message {
            background-color: #ffcccc; /* Piros hátterű */
            color: #990000; /* Sötétvörös szöveg */
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .my-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #5fb34f;
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
        <h1>Összes kép</h1>
        
        <div class="gallery">
        <?php
            // Képek bejárása és megjelenítése
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image_name = $row['image_name'];
                    $uploaded_by = $row['uploaded_by'];
                    $uploaded_at = $row['uploaded_at'];

                    echo '<div class="image-wrapper">';
                    echo '<img src="' . $image_folder . $image_name . '" alt="Kép">';
                    echo '<div class="image-info">' . $uploaded_by . ' - ' . $uploaded_at . '</div>'; // Feltöltő és feltöltési időpont
                    echo '</div>';
                }
            } else {
                echo "Nincsenek feltöltött képek.";
            }
        ?>
        </div>
        
        <br>
        <a href="index.php" class="my-button">Vissza a főoldalra</a>
        <a href="index.php?oldal=upload" class="my-button">Kép feltöltése</a>
        <a href="index.php?oldal=gallery" class="my-button">Saját Képek</a>
    </div>
</body>
</html>
