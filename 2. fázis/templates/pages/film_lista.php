<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');
// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['username'])) {
    header("Location: index.php?oldal=login");
    exit();
}

// Ellenőrizzük, hogy az értékelés gombra kattintottak-e
if (isset($_POST['ertekeles_submit'])) {
    if (isset($_POST['film_id']) && isset($_POST['ertekeles'])) {
        $film_id = $_POST['film_id'];
        $ertekeles = $_POST['ertekeles'];

        // Ellenőrizzük, hogy a felhasználó még nem értékelte-e már ezt a filmet
        $username = $_SESSION['username'];
        $check_query = "SELECT * FROM film_ertekeles WHERE film_id = $film_id AND felhasznalo = '$username'";
        $result = $conn->query($check_query);
        if ($result->num_rows == 0) {
            // Ha még nem értékelte, akkor beszúrjuk az adatbázisba
            $insert_query = "INSERT INTO film_ertekeles (film_id, felhasznalo, ertekeles) VALUES ($film_id, '$username', $ertekeles)";
            if ($conn->query($insert_query) === TRUE) {
                // Sikeres értékelés
            } else {
                echo "Hiba az értékelés során: " . $conn->error;
            }
        }
    }
}

// Ellenőrizzük, hogy az értékelés törlése gombra kattintottak-e
if (isset($_POST['ertekeles_torles'])) {
    if (isset($_POST['film_id'])) {
        $film_id = $_POST['film_id'];

        // Ellenőrizzük, hogy a felhasználó értékelte-e már ezt a filmet
        $username = $_SESSION['username'];
        $check_query = "SELECT * FROM film_ertekeles WHERE film_id = $film_id AND felhasznalo = '$username'";
        $result = $conn->query($check_query);
        if ($result->num_rows > 0) {
            // Ha értékelte, akkor töröljük az értékelést az adatbázisból
            $delete_query = "DELETE FROM film_ertekeles WHERE film_id = $film_id AND felhasznalo = '$username'";
            if ($conn->query($delete_query) === TRUE) {
                // Sikeres törlés
            } else {
                echo "Hiba az értékelés törlésekor: " . $conn->error;
            }
        }
    }
}

// Keresés feldolgozása
if (isset($_POST['kereses_submit'])) {
    $kereses = $_POST['kereses'];

    // Keresés a filmek között
    $sql = "SELECT filmek.*, AVG(film_ertekeles.ertekeles) AS atlag_ertekeles FROM filmek LEFT JOIN film_ertekeles ON filmek.id = film_ertekeles.film_id WHERE filmek.cim LIKE '%$kereses%' GROUP BY filmek.id ORDER BY filmek.megjelenes_datum DESC";
} else {
    // Filmek lekérdezése az adatbázisból
    $sql = "SELECT filmek.*, AVG(film_ertekeles.ertekeles) AS atlag_ertekeles FROM filmek LEFT JOIN film_ertekeles ON filmek.id = film_ertekeles.film_id GROUP BY filmek.id ORDER BY filmek.megjelenes_datum DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <link rel="stylesheet" href="css/style.css"> <!-- CSS stílusok -->
    <title>Filmek lista</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    background-image: url('pictures/hatter.jpg');
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 20px auto;
    background-color: rgba(255, 255, 255, 0.8);
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

.film-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Középre igazítás */
}

.film-item {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    flex: 0 0 calc(33.33% - 20px); /* 33.33%-os szélesség a flexbox-ban */
    max-width: calc(33.33% - 20px); /* 33.33%-os szélesség a flexbox-ban */
    overflow: hidden; /* a túlcsorduló tartalom elrejtése */
}

.film-item img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 15px;
}

.film-content {
    padding: 10px;
    max-height: 200px; /* Maximális magasság beállítása */
    overflow-y: auto; /* Görgetősáv hozzáadása, ha a tartalom túl nagy */
}

.film-content h2 {
    margin-top: 0;
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #000;
}

.film-content p {
    margin: 0; /* nullázzuk a margin-t */
    line-height: 1.4; /* növeljük a sortávot a jobb olvashatóság érdekében */
}

.buttons {
    display: flex;
    justify-content: space-between;
}

.my-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4fb088;
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
        /* A többi CSS stílus itt található */

        .film-ertekeles {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .film-atlag-ertekeles {
            margin-top: 5px;
            font-style: italic;
        }

        .delete-button {
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            padding: 5px 10px;
            margin-top: 5px;
        }

        .delete-button:hover {
            background-color: #e60000;
        }
        .search-bar input[type="text"] {
            flex: 1; /* Az input kitölti a rendelkezésre álló helyet */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 16px;
            box-sizing: border-box; /* Tartalom + padding + border méret beállítása */
        }

        .search-bar input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            text-decoration: none; /* Betű alatti vonal eltávolítása */
        }

        .search-bar input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Filmek listája</h1>
        <form action="index.php?oldal=film_lista" method="post" style="margin-bottom: 20px;" class="search-bar">
            <input type="text" name="kereses" placeholder="Keresés a filmek között">
            <input type="submit" value="Keresés" name="kereses_submit">
            <input type="submit" value="Összes Film Mutatása" name="kereses_submit">
        </form>
        <ul class="film-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="film-item">';
                    echo '<img src="' . $row['borito_kep'] . '" alt="' . $row['cim'] . '">';
                    echo '<div class="film-content">';
                    echo '<h2>' . $row['cim'] . '</h2>';
                    echo '<p><strong>Rendező:</strong> ' . $row['rendezo'] . '</p>';
                    echo '<p><strong>Szereplők:</strong> ' . $row['szereplok'] . '</p>';
                    echo '<p><strong>Megjelenés:</strong> ' . $row['megjelenes_datum'] . '</p>';
                    echo '<p><strong>Leírás:</strong> ' . $row['leiras'] . '</p>';

                    // Értékelési űrlap
                    echo '<form action="index.php?oldal=film_lista" method="post" class="film-ertekeles">';
                    echo '<input type="hidden" name="film_id" value="' . $row['id'] . '">';
                    echo '<label for="ertekeles">Értékelés:</label>';
                    echo '<select name="ertekeles" id="ertekeles">';
                    echo '<option value="1">1</option>';
                    echo '<option value="2">2</option>';
                    echo '<option value="3">3</option>';
                    echo '<option value="4">4</option>';
                    echo '<option value="5">5</option>';
                    echo '</select>';
                    echo '<br>';
                    echo '<input type="submit" value="Értékelés" name="ertekeles_submit" style="background-color: green; color: white;">';
                    echo '</form>';

                    // Értékelés törlése gomb
                    echo '<form action="index.php?oldal=film_lista" method="post">';
                    echo '<input type="hidden" name="film_id" value="' . $row['id'] . '">';
                    echo '<input type="submit" value="Törlés" name="ertekeles_torles" class="delete-button">';
                    echo '</form>';

                    // Átlagos értékelés
                    echo '<p class="film-atlag-ertekeles">Átlagos értékelés: ' . round($row['atlag_ertekeles'], 1) . '</p>';

                    // Szerkesztés gomb
                    echo '<div class="buttons">';
                    echo '<a class="my-button" href="index.php?oldal=film_szerkesztese&id=' . $row['id'] . '">Szerkesztés</a>';
                    echo '</div>';
                    
                    
                    echo '</div>'; // film-content
                    echo '</li>'; // film-item
                }
            } else {
                echo "<p>Nincsenek filmek a listában, vagy elírtad a film címét</p>";
            }
            ?>
        </ul>
        <br>
        <a href="index.php?oldal=film_hozaadasa" class="my-button">Új film hozzáadása</a>
        <a href="index.php" class="my-button">Vissza a főoldalra</a>
        <br>
    </div>
</body>
</html>

