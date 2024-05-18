<?php
session_start();
require_once('/var/www/customers/vh-74184/web/home/web/includes/config.php');

// Üzenet megjelenítése
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ellenőrizzük a felhasználónév és jelszó helyességét
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Ellenőrizzük az adatbázisban
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Sikeres bejelentkezés, beállítjuk a session változókat
                $_SESSION['user_id'] = $row['id']; // Felhasználó azonosítója
                $_SESSION['username'] = $username; // Felhasználó neve
                header('Location: index.php');
                exit();
            } else {
                // Hibás jelszó
                $message = '<div style="background-color: #ffcccc; padding: 10px;">Hibás felhasználónév vagy jelszó!</div>';
            }
        } else {
            // Hibás felhasználónév
            $message = '<div style="background-color: #ffcccc; padding: 10px;">Hibás felhasználónév vagy jelszó!</div>';
        }

        $stmt->close();
    } else {
        // Felhasználónév vagy jelszó nincs megadva
        $message = '<div style="background-color: #ccffcc; padding: 10px;">Kérjük, írja be a felhasználónevet és a jelszavát!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures/icon.jpg">
    <title>Bejelentkezés</title>
    <style>
        body {
            background-image: url('pictures/hatter.jpg'); /* Háttérkép beállítása */
            background-size: cover; /* Háttérkép méretének beállítása */
            background-position: center; /* Háttérkép pozíciójának beállítása */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Átlátszó háttér */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 50px; /* Üzenet alatt */
        }

        h1 {
            color: #007bff;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .my-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none; /* Betű alatti vonal eltávolítása */
        }

        .my-button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-bottom: 20px; /* Üzenet alatt */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="message"><?php echo $message; ?></div> <!-- Üzenet itt -->
        <h1>Bejelentkezés</h1>
        <form action="index.php?oldal=login" method="POST">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username">
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="Bejelentkezés">
        </form>
        <br>
        <a href="index.php" class="my-button">Vissza a főoldalra</a>
    </div>
</body>
</html>
