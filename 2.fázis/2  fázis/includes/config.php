<?php
$servername = "mysql.nethely.hu";
$username = "filmnethely";
$password = "MolnarDancsak20240419";
$dbname = "filmnethely";

$conn = new mysqli($servername, $username, $password, $dbname);

$menu = array(
    '/' => array('fajl' => 'index', 'szoveg' => 'Főoldal', 'menun' => array(0,1)),
    'all_images' => array('fajl' => 'all_images', 'szoveg' => 'Galéria', 'menun' =>array(1,1)),
    'gallery' => array('fajl' => 'gallery', 'szoveg' => 'Saját Képek', 'menun' => array(0,1)),
    'upload' => array('fajl' => 'upload', 'szoveg' => 'Feltöltés', 'menun' => array(1,1)),
    'urlap' => array('fajl' => 'urlap', 'szoveg' => 'Kapcsolat', 'menun' => array(1,1)),
    'messages' => array('fajl' => 'messages', 'szoveg' => 'Üzenetek', 'menun' => array(1,1)),
    'delete_account' => array('fajl' => 'delete_account', 'szoveg' => 'Fiók Törlés', 'menun' => array(0,0)),
    'torles' => array('fajl' => 'delete', 'szoveg' => 'Törlés', 'menun' => array(0,0)),
    'film_hozaadasa' => array('fajl' => 'film_hozaadasa', 'szoveg' => 'Film Hozzáadása', 'menun' => array(0,0)),
    'film_lista' => array('fajl' => 'film_lista', 'szoveg' => 'Film Adatbázis', 'menun' => array(0,0)),
    'film_mentes' => array('fajl' => 'film_mentese', 'szoveg' => 'Film Mentése', 'menun' => array(0,0)),
    'film_szerkesztese' => array('fajl' => 'film_szerkesztese', 'szoveg' => 'Film Szerkesztése', 'menun' => array(0,0)),
    'film_torles' => array('fajl' => 'film_torlese', 'szoveg' => 'Film Törlése', 'menun' => array(0,0)),
    'login' => array('fajl' => 'login', 'szoveg' => 'Bejelentkezés', 'menun' => array(0,0)),
    'register' => array('fajl' => 'register', 'szoveg' => 'Regisztrálás', 'menun' => array(0,0)),
    'submit_form' => array('fajl' => 'submit_form', 'szoveg' => 'Űrlap Kitöltése', 'menun' => array(0,0)),
    'logout' => array('fajl' => 'logout', 'szoveg' => '', 'menun' => array(0,0)),
    'delete_message' => array('fajl' => 'delete_message', 'szoveg' => '', 'menun' => array(0,0)),
);

$hiba_oldal = array ('fajl' => '404', 'szoveg' => 'A keresett oldal nem található!');
?>
