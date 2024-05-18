<?php
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy van-e üzenet az URL-ben
if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];

    // Töröljük az üzenetet az adatbázisból
    $stmt_delete = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt_delete->bind_param("i", $delete_id);
    if ($stmt_delete->execute()) {
        // Sikeres törlés esetén "success" üzenet küldése
        echo "success";
    } else {
        // Sikertelen törlés esetén "error" üzenet küldése
        echo "error";
    }
} else {
    // Ha nincs megadva üzenet azonosító, "missing_id" üzenet küldése
    echo "missing_id";
}
?>
