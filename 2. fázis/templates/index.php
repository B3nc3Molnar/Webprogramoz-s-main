<?php
session_start();?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./pictures/icon.jpg">
    <title>Főoldal</title>
    <!-- CSS fájlok importálása -->
    <link rel="stylesheet" href="style.css">
    <!-- JavaScript fájlok importálása -->
    <script src="../js/script.js" defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('pictures/hatter.jpg');
            background-size: auto;
            background-repeat: repeat;
            color: #fff;
        }

        .menu {
            width: 200px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin-bottom: 10px;
        }

        .menu a {
            color: #fff;
            text-decoration: none;
        }

        .menu a:hover {
            text-decoration: underline;
        }

        .menu.show {
            transform: translateX(0);
        }

        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            font-size: 24px;
            color: #f1c40f; /* Sárga szín */
            cursor: pointer;
            z-index: 999;
        }
        h2 {
    color: #bfdd16;
    font-size: 35px;
    margin-bottom: 10px;
    text-align: center;
}
        footer {
    text-align: center;
    background-color: #333;
    color: #fff; /* Sötétebb betűszín */
    padding: 10px 0; /* Kis belső térköz */
    width: 100%; /* Teljes szélesség */
    font-size: 16px; /* Betűméret */
}
    </style>
</head>
<body>
<div class="header">
    <a href="index.php?oldal=film_lista"><h1 style="font-size: 36px;">Film Adatbázis</h1></a>
</div>
<?php
include 'header.php';
?>
    <nav>
        <div class="menu">
        <br>
        <br>
        <h2>Felhasználói Panel</h2>
        <ul>
            <br>
            <br>
            <li><a href="index.php?oldal=register">Regisztráció</a></li>
            <br>
            <br>
            <li><a href="index.php?oldal=gallery">Saját képek</a></li>
            <br>
            <br>
            <li><a href="index.php?oldal=delete_account">Fiók törlés</a></li>
        </ul>
    </div>
    <div class="menu-toggle" onclick="toggleMenu()" style="background-color: #d0ff00; padding: 5px; border-radius: 50%; color: black; cursor: pointer;">☰</div>
    <script>
        function toggleMenu() {
            var menu = document.querySelector('.menu');
            menu.classList.toggle('show');
        }
    </script>
        
        <?php
if (isset($_SESSION['username'])) {
    echo "<p></p>";
    echo "<p style='font-size: 24px; color:white;'>Üdvözöllek, " . $_SESSION['username'] . "!</p>";
} else {
    echo "<strong style='color: red; font-size: 24px;'> <p>Üdvözöllek Vendég! Kérlek jelentkezz be, vagy regisztrálj az oldalra a további tartalmak eléréséhez!</p> </strong>";
}
?>

    </nav>
    <div class="hero" style="background-color: rgba(255, 255, 255, 0.5); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <h2 style="font-size: 28px; color: #333; margin-bottom: 15px;">Üdvözöljük a Filmkeresőben!</h2>
    <p style="font-size: 18px; color: #000; background-color: #bfd7db ; line-height: 1.5;">Fedezze fel a végtelen filmvilágot a Filmkereső segítségével! Legyen szó klasszikusokról vagy legújabb blockbuster-ekről, nálunk minden film megtalálható. Böngésszen a legjobb kategóriákban, találjon új kedvenceket, és merüljön el a mozgókép mágikus világában.<br>

A Filmkereső egy olyan platform, ahol könnyedén megtalálhatja a legfrissebb filmeket, valamint az ikonikus klasszikusokat. Küldhet és megoszthat képeket, értékeléseket, és akár saját filmeket is feltölthet, hogy mások is láthassák és értékelhessék. Legyen Ön egy szenvedélyes filmrajongó vagy csak egy kikapcsolódásra vágyó, itt mindenki megtalálja a számára megfelelő filmet.<br>

Engedje, hogy a Filmkereső vezesse Önt az új filmek és a kultikus alkotások világában. Legyen részese az élménynek és hagyja, hogy az itt található közösség segítségével felfedezze a legjobb filmeket. Hajrá, induljon el az utazásra már ma, és élvezze a filmek világát a Filmkereső segítségével!<br>







</p>
</div>



   <!-- Videók -->
<section id="videos">
    <h3 style="color: #d0ff00; font-size: 24px; background-color: #4e5a5c; padding: 5px; border-radius: 4px;">Legújabb Trailerek és Üdvözlő videónk: </h3>
    
    <!-- Szolgáltatótól -->
    <iframe width="420" height="315" src="https://www.youtube.com/embed/0r5xNewL1No"></iframe>
        
    
    <!-- Saját könyvtárból -->
    <video controls width="420" height="315">
        <source src="pictures\VIDEO.mp4" type="video/mp4">
        A böngésződ nem támogatja a videót.
    </video>
    
    <iframe width="420" height="315" src="https://www.youtube.com/embed/Ju2gSTs5WpQ"></iframe>
</section>


    <!-- Google térkép -->
    <section id="map">
    <h3 style="color: #d0ff00; font-size: 24px; background-color: #4e5a5c ; padding: 5px; border-radius: 4px;">Google Térkép</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2785.0974324893467!2d19.667734715839603!3d46.895948122423074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4742e50d51a9c715%3A0x8224988fb9d2a13e!2sGAMF%20Faculty%20of%20Engineering%20and%20Computer%20Science%2C%20University%20of%20Kecskem%C3%A9t!5e0!3m2!1sen!2shu!4v1649990078343!5m2!1sen!2shu" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </section>

    <footer>
        <p>&copy; 2024 Filmkereső</p>
    </footer>
</body>
</html>
   