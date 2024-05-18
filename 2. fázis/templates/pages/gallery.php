<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');



// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['username'])) {
    header('Location: index.php?oldal=login');
    exit();
}

// Ha a kép törlési űrlap elküldésre került
if (isset($_POST['delete_image'])) {
    $image_name = $_POST['image_name'];

    // Ellenőrizzük, hogy a képet feltöltő felhasználó törölni akarja-e a képet
    $stmt = $conn->prepare("SELECT * FROM uploaded_images WHERE image_name = ? AND uploaded_by = ?");
    $stmt->bind_param("ss", $image_name, $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ha a felhasználó a képet törölheti
    if ($result->num_rows > 0) {
        // Töröljük a képet az adatbázisból
        $delete_stmt = $conn->prepare("DELETE FROM uploaded_images WHERE image_name = ?");
        $delete_stmt->bind_param("s", $image_name);
        if ($delete_stmt->execute()) {
            // A kép sikeresen törölve lett, töröljük a fájlt is a szerverről
            $image_path = "pictures/" . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path); // Képfájl törlése
            }
            // Frissítjük az oldalt
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Hiba történt a kép törlése közben.";
        }
    } else {
        echo "Nem vagy jogosult törölni ezt a képet.";
    }
}

// Képek lekérdezése az adatbázisból
if (isset($_GET['all_images'])) {
    $sql = "SELECT * FROM uploaded_images ORDER BY uploaded_at DESC";
} else {
    $sql = "SELECT * FROM uploaded_images WHERE uploaded_by = ? ORDER BY uploaded_at DESC";
}
$stmt = $conn->prepare($sql);
if (!isset($_GET['all_images'])) {
    $stmt->bind_param("s", $_SESSION['username']);
}
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
    <title>Képgaléria</title>
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

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            display: none; /* Alapértelmezés szerint a törlés gomb rejtve van */
        }

        .image-wrapper:hover .delete-button {
            display: block; /* Ha a képre mutat a felhasználó, megjelenítjük a törlés gombot */
        }

        .image-wrapper {
            position: relative;
            width: 100%;
            max-width: 300px; /* Maximális képszélesség */
        }

        .image-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Fekete áttetsző háttér */
            color: #fff;
            font-size: 12px;
            padding: 5px;
            box-sizing: border-box;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
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
        <h1>Képgaléria</h1>
        
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
                    echo '<form action="" method="post">';
                    echo '<input type="hidden" name="image_name" value="' . $image_name . '">';
                    if ($uploaded_by === $_SESSION['username']) {
                        echo '<button type="submit" name="delete_image" class="delete-button">X</button>';
                    }
                    echo '</form>';
                    echo '<div class="image-info">' . $uploaded_by . ' - ' . $uploaded_at . '</div>'; // Feltöltő és feltöltési időpont
                    echo '</div>';
                }
            } else {
                echo "Nincsenek feltöltött képek.";
            }
        ?>
        </div>

        <?php if (isset($_GET['permission_error'])): ?>
            <div class="error-message">Nem vagy jogosult törölni ezt a képet.</div>
        <?php endif; ?>
        
        <br>
        <a href="index.php" class="my-button">Vissza a főoldalra</a>
        <a href="index.php?oldal=upload" class="my-button">Kép feltöltése</a>
        <a href="index.php?oldal=all_images" class="my-button">Összes kép</a>
    </div>
</body>
</html>
