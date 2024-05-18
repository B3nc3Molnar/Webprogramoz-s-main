<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Ellenőrizzük, hogy be van-e jelentkezve a felhasználó
if (!isset($_SESSION['username'])) {
    header("Location: index.php?oldal=login");
    exit();
}

// Ellenőrizzük, hogy a kép törlési gombja lett-e megnyomva
if (isset($_POST['delete_image'])) {
    // Ellenőrizzük, hogy az image_name értéke létezik-e
    if (isset($_POST['image_name'])) {
        $image_name = $_POST['image_name'];
        
        // Kép törlése az adatbázisból
        $stmt = $conn->prepare("DELETE FROM uploaded_images WHERE image_name = ?");
        $stmt->bind_param("s", $image_name);

        if ($stmt->execute()) {
            // Ellenőrizzük, hogy a kép létezik-e a megadott mappában
            $image_folder = "pictures/";
            $image_path = $image_folder . $image_name;
            if (file_exists($image_path)) {
                // Töröljük a képet
                unlink($image_path);
                header("Location: index.php?oldal=gallery");
                exit();
            } else {
                echo "A kép nem található.";
            }
        } else {
            echo "Hiba történt a kép törlése során.";
        }

        $stmt->close();
    } else {
        echo "Nincs megadva kép az eltávolításhoz.";
    }
} else {
    header("Location: index.php?oldal=gallery");
    exit();
}
?>
