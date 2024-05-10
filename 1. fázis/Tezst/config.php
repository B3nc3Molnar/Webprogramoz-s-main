<?php
$servername = "mysql.nethely.hu";
$username = "filmnethely";
$password = "MolnarDancsak20240419";
$dbname = "filmnethely";

$menu = array(
    'Főoldal' => '../fooldal/index.php',
    'Kép Galéria' => '../kepgaleria/gallery.php',
    'Kép Feltöltés' => '../kepgaleria/upload.php',
    'Űrlap' => '../urlap/urlap.php',
    'Üzeneteid' => '../urlap/messages.php'
);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
