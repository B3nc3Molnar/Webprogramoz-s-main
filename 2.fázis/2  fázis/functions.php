<?php

// functions.php

// Ha nincs aktív munkamenet, akkor indítsd újra
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Felhasználó bejelentkezésének ellenőrzése
function is_logged_in() {
    return isset($_SESSION['username']);
}

// Felhasználó kijelentkeztetése
function logout() {
    // Munkamenet változóinak törlése
    $_SESSION = array();

    // Munkamenet lezárása és törlése
    session_destroy();
}
?>