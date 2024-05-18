<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

if (!isset($_SESSION['username'])) {
   header("Location: index.php?oldal=login")
    exit();
}

if (isset($_GET['id'])) {
    $film_id = $_GET['id'];

    // Először törölni kell az összes értékelést, ami ehhez a filmhez tartozik
    $delete_ertekeles_query = "DELETE FROM film_ertekeles WHERE film_id = $film_id";
    if ($conn->query($delete_ertekeles_query) === TRUE) {
        // Ezután törölhetjük magát a filmet
        $delete_film_query = "DELETE FROM filmek WHERE id = $film_id";
        if ($conn->query($delete_film_query) === TRUE) {
            header("Location: index.php?oldal=film_lista");
            exit();
        } else {
            echo "Hiba történt az adatbázis film törlése során: " . $conn->error;
        }
    } else {
        echo "Hiba történt az adatbázis értékelések törlése során: " . $conn->error;
    }
} else {
    echo "Nem megfelelő hívás.";
}
?>

