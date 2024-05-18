<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['username'])) {
    header("Location: index.php?oldal=login");
    exit();
}

if (isset($_GET['id'])) {
    $film_id = $_GET['id'];

    // Film adatainak lekérdezése az adatbázisból
    $query = "SELECT * FROM filmek WHERE id = $film_id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $cim = $row['cim'];
        $rendezo = $row['rendezo'];
        $megjelenes_datum = $row['megjelenes_datum'];
        $leiras = $row['leiras'];
        $szereplok = $row['szereplok'];
    } else {
        echo "Hiba történt az adatok lekérdezésekor.";
        exit();
    }
} else {
    echo "Nem megfelelő hívás.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limitáljuk a mezőket
    $rendezo_limit = 100;
    $cim_limit = 100;
    $leiras_limit = 1000;
    $szereplok_limit = 500;

    // Ellenőrizzük, hogy minden mező kitöltve van-e
    if (empty($_POST['cim']) || empty($_POST['rendezo']) || empty($_POST['megjelenes_datum']) || empty($_POST['leiras']) || empty($_POST['szereplok'])) {
        echo "<div class='message error'>Minden mezőt ki kell tölteni!</div>";
    } else {
        $cim = substr($_POST['cim'], 0, $cim_limit);
        $rendezo = substr($_POST['rendezo'], 0, $rendezo_limit);
        $megjelenes_datum = $_POST['megjelenes_datum'];
        $leiras = substr($_POST['leiras'], 0, $leiras_limit);
        $szereplok = substr($_POST['szereplok'], 0, $szereplok_limit);

        // Ellenőrizzük, hogy nem lépték-e túl a karakterkorlátot
        if (strlen($cim) > $cim_limit || strlen($rendezo) > $rendezo_limit || strlen($leiras) > $leiras_limit || strlen($szereplok) > $szereplok_limit) {
            echo "<div class='message error'>Valamelyik mező túl hosszú! A cím, rendező maximum 100 karakter, a leírás maximum 1000 karakter, a szereplők maximum 500 karakter lehet.</div>";
        } else {
            // Film adatainak frissítése az adatbázisban
            $update_query = "UPDATE filmek SET cim = '$cim', rendezo = '$rendezo', megjelenes_datum = '$megjelenes_datum', leiras = '$leiras', szereplok = '$szereplok' WHERE id = $film_id";
            if ($conn->query($update_query) === TRUE) {
                header("Location: index.php?oldal=film_lista");
                exit();
            } else {
                echo "Hiba történt az adatok frissítésekor: " . $conn->error;
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
    <title>Film szerkesztése</title>
    <link rel="stylesheet" href="style.css">
    <style>
         body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('pictures/hatter.jpg');
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
    background-color: rgba(18, 225, 208, 0.5); /* Áttetsző háttérszín */
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}


        h1 {
            color: #000;
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

        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: none;
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
            background-color: #ba509c;
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

        .message {
            color: #ff0000;
            margin-bottom: 10px;
        }
        </style>
</head>
<body>

    <div class="container">
        <h1>Film szerkesztése</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$film_id"; ?>" method="post">
            <label for="cim">Cím:</label><br>
            <input type="text" id="cim" name="cim" value="<?php echo $cim; ?>"><br>
            <label for="rendezo">Rendező:</label><br>
            <input type="text" id="rendezo" name="rendezo" value="<?php echo $rendezo; ?>"><br>
            <label for="megjelenes_datum">Megjelenés dátuma:</label><br>
            <input type="date" id="megjelenes_datum" name="megjelenes_datum" value="<?php echo $megjelenes_datum; ?>"><br>
            <label for="leiras">Leírás:</label><br>
            <textarea id="leiras" name="leiras"><?php echo $leiras; ?></textarea><br>
            <label for="szereplok">Szereplők:</label><br>
            <input type="text" id="szereplok" name="szereplok" value="<?php echo $szereplok; ?>"><br>
            <input type="submit" value="Mentés" name="submit" style="background-color: green; color: white;">
        </form>
        <br>
        <a href="index.php" class="my-button">Vissza a főoldalra</a>
    </div>
</body>
</html>
