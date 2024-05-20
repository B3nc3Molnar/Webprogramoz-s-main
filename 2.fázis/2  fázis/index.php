<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include('./includes/config.php');

$keres = $menu['/']; // Alapértelmezett oldal

if (isset($_GET['oldal'])) {
    $oldal_nev = $_GET['oldal'];
    
    // Ha a kért oldal létezik a menüben és a fájl is megtalálható
    if (isset($menu[$oldal_nev]) && file_exists("./templates/pages/{$menu[$oldal_nev]['fajl']}.php")) {
        $keres = $menu[$oldal_nev];
        include ("./templates/pages/{$menu[$oldal_nev]['fajl']}.php");
    } else { 
        // Ha nem található az oldal a menüben, akkor 404 hibát dobunk
        $keres = $hiba_oldal;
        header("HTTP/1.0 404 Not Found");
        include ("./templates/pages/404.php"); // 404 oldal betöltése
    }
} else {
    include('./templates/index.php');
}
?>
