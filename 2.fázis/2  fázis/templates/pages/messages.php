<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy van-e üzenet az URL-ben és az üzenet létezik-e
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Töröljük az üzenetet az adatbázisból, csak ha a felhasználó be van jelentkezve és az övé az üzenet
        $stmt_delete = $conn->prepare("DELETE FROM messages WHERE id = ? AND username = ?");
        $stmt_delete->bind_param("is", $delete_id, $username);
        if ($stmt_delete->execute()) {
            // Sikeres törlés után frissítjük az oldalt
            header("Location: index.php?oldal=messages");
            exit();
        } else {
            echo "Hiba történt az üzenet törlése során.";
        }
    } else {
        echo "Nincs jogosultságod törölni ezt az üzenetet.";
    }
}

// Lekérdezzük az összes üzenetet az adatbázisból rendezve dátum szerint csökkenő sorrendben
$stmt = $conn->prepare("SELECT * FROM messages ORDER BY created_at DESC");
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
        <p>Itt láthatóak az üzenetek:</p>
        <ul>
            <?php
            // Ellenőrizzük, hogy vannak-e üzenetek
            if ($result->num_rows > 0) {
                // Megjelenítjük az összes üzenetet
                while ($row = $result->fetch_assoc()) {
                    echo "<p><strong>Felhasználó:</strong> " . $row['username'] . "</p>";
                    echo "<p><strong>Üzenet:</strong> " . $row['message'] . "</p>";
                    echo "<p><strong>Dátum:</strong> " . $row['created_at'] . "</p>";
                    // Törlés gomb, csak bejelentkezett felhasználónak és a saját üzenetére
                    if (isset($_SESSION['username']) && $_SESSION['username'] == $row['username']) {
                        echo "<a href='index.php?oldal=messages&delete_id=" . $row['id'] . "'>Törlés</a>";
                    }
                    echo "<hr>";
                }
            } else {
                echo '<div style="text-align: center;">';
                echo "Nincsenek üzenetek.";
                echo "</div>";
            }

            $stmt->close();
            ?>
        </ul>
        <p>Ugrás a Főoldalra <a href="index.php">Főoldal</a>.</p>
    </div>
</body>
</html>
