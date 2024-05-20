<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');



if (isset($_POST['szerkesztes_submit'])) {
    $film_id = $_POST['film_id'];
    $cim = $_POST['cim'];
    $rendezo = $_POST['rendezo'];
    // A többi adatmező feldolgozása

    $sql = "UPDATE filmek SET cim = '$cim', rendezo = '$rendezo' WHERE id = $film_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?oldal=film_lista");
        exit();
    } else {
        echo "Hiba történt az adatbázis frissítése során: " . $conn->error;
    }
} else {
    echo "Nem megfelelő hívás.";
}
?>
