<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <link rel="stylesheet" href="css/style.css"> 
    <script src="script.js" defer></script>
    <title>Képfeltöltő</title>
    <style>
        /* Az előző CSS-stílusok itt helyezhetők el */
        body {
            background-image: url('pictures/hatter.jpg');
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #4CAF50;
            color: white;
        }

        .message.error {
            background-color: #f44336;
            color: white;
        }
    

        .container {
            background-color: #d98211;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #0e35e3;
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
<?php
session_start();
require_once('includes/config.php'); // Az adatbázis kapcsolatot tartalmazó fájl elérési útja

// Ellenőrizzük, hogy a feltöltés gombra kattintottak-e és a kép meg lett-e adva
if (isset($_POST['submit'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];

        // Ellenőrizzük, hogy a fájl egy kép-e
        $image_info = getimagesize($image_tmp_name);
        if ($image_info === false) {
            echo "<div class='message error'>Csak kép fájlok tölthetőek fel!</div>";
            exit();
        }

        // Engedélyezzük csak bizonyos képtípusokat
        $allowed_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
        if (!in_array($image_info[2], $allowed_types)) {
            echo "<div class='message error'>Csak JPG, PNG vagy GIF képeket lehet feltölteni!</div>";
            exit();
        }

        // Ellenőrizzük a fájlméretet
        $max_file_size = 5 * 1024 * 1024; // 5 MB
        if ($image_size > $max_file_size) {
            echo "<div class='message error'>A fájl mérete túl nagy! Maximum 5 MB lehet.</div>";
            exit();
        }

        // Fájlnév egyediítése
        $unique_image_name = uniqid('image_', true) . '.' . pathinfo($image_name, PATHINFO_EXTENSION);

        // Fájl elérési útvonala
        $target_dir = "pictures/";
        $target_file = $target_dir . $unique_image_name;

        // A fájl mozgatása a célmappába
        if (move_uploaded_file($image_tmp_name, $target_file)) {
            // Sikeres feltöltés
            echo "<div class='message success'>A kép sikeresen feltöltve: " . $unique_image_name . "</div>";

            // Adatbázisba való mentés
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Vendég';
            $uploaded_by = $username;
            $uploaded_at = date('Y-m-d H:i:s'); // A jelenlegi dátum és idő

            // SQL lekérdezés a kép hozzáadására az adatbázishoz
            $stmt = $conn->prepare("INSERT INTO uploaded_images (image_name, uploaded_by, uploaded_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $unique_image_name, $uploaded_by, $uploaded_at);

            if ($stmt->execute()) {
                // Sikeresen beszúrva az adatbázisba
                echo "<div class='message success'>A kép sikeresen hozzáadva az adatbázishoz.</div>";
            } else {
                // Hiba az adatbázisba való beszúrás közben
                echo "<div class='message error'>Hiba történt az adatbázisba való beszúrás során.</div>";
            }
            $stmt->close();
        } else {
            // Hiba a fájl mozgatása közben
            echo "<div class='message error'>Hiba történt a feltöltés során.</div>";
        }
    } else {
        // Kép nem lett megadva
        echo "<div class='message error'>Nincs kiválasztott kép!</div>";
    }
}
?>
<div class="container">
    <h1>Képfeltöltő</h1>
    <form action="index.php?oldal=upload" method="post" enctype="multipart/form-data">
        Válassz képet a feltöltéshez:
        <input type="file" name="image" id="image">
        <input type="submit" value="Feltöltés" name="submit">
    </form>
    <br>
    <a href="index.php?oldal=all_images" class="my-button">Kép Galéria</a>
    <a href="index.php" class="my-button">Vissza a főoldalra</a>
</div>
</body>
</html>
