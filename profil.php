<?php
session_start(); // Starte die Sitzung

// Überprüfe, ob der Benutzer angemeldet ist
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Speichere die E-Mail-Adresse in einer Variablen
$email = $_SESSION['email'];

// Verbindung zur Datenbank herstellen
$connection = mysqli_connect("localhost", "root", "", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfe, ob eine Datei hochgeladen wurde
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Erstelle einen Zielpfad für das Bild
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Überprüfe, ob die Datei ein Bild ist
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            // Verschiebe die hochgeladene Datei in den Zielordner
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                // Aktualisiere den Bildpfad in der Datenbank
                $sql = "UPDATE users SET profile_picture = '$targetFile' WHERE email = '$email'";
                if (mysqli_query($connection, $sql)) {
                    echo "<div class='alert success'>Profilbild wurde hochgeladen.</div>";
                } else {
                    echo "<div class='alert error'>Fehler beim Speichern des Profilbildes.</div>";
                }
            } else {
                echo "<div class='alert error'>Fehler beim Hochladen der Datei.</div>";
            }
        } else {
            echo "<div class='alert error'>Die Datei ist kein Bild.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
            color: #444;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        input[type="password"],
        input[type="text"],
        input[type="file"],
        button[type="submit"],
        button.logout-button {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: #a777e3;
            box-shadow: 0 0 8px rgba(167, 119, 227, 0.3);
        }

        button[type="submit"] {
            background-color: #a777e3;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #9057c9;
            transform: translateY(-2px);
        }

        button.logout-button {
            background-color: #f44336;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.logout-button:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .picture-container input[type="file"] {
            padding: 0;
            font-size: 16px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 30px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profil Einstellungen</h1>
        <p><strong>Benutzername (E-Mail):</strong> <?php echo htmlspecialchars($email); ?></p>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="password-container">
                <input type="password" name="old_password" id="old_password" placeholder="Altes Passwort" required>
                <span class="toggle-password" onclick="togglePassword('old_password')">👁️</span>
            </div>
            <div class="password-container">
                <input type="password" name="new_password" id="new_password" placeholder="Neues Passwort" required>
                <span class="toggle-password" onclick="togglePassword('new_password')">👁️</span>
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Passwort bestätigen" required>
                <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
            <div class="picture-container">
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
            </div>
            <button type="submit">Passwort ändern und Profilbild hochladen</button>
        </form>
        <button class="logout-button" onclick="location.href='index.php';">Zurück zu den Codes</button>
    </div>
    <script>
        function togglePassword(fieldId) {
            var field = document.getElementById(fieldId);
            var fieldType = field.getAttribute('type');
            field.setAttribute('type', fieldType === 'password' ? 'text' : 'password');
        }
    </script>
</body>
</html>
