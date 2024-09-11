<?php
session_start(); // Start the session

// Überprüfen, ob der Administrator bereits angemeldet ist
if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_panel.php");
    exit();
}

// Wenn das Anmeldeformular gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verbindung zur MySQL-Datenbank herstellen
    $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
    if (!$connection) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
    }

    // Benutzerdaten aus dem Formular abrufen
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL-Abfrage zum Überprüfen der Anmeldeinformationen des Administrators
    $sql = "SELECT * FROM users WHERE email=? AND role='admin'";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Überprüfen, ob ein Administrator mit den angegebenen Anmeldeinformationen existiert
    if ($row = mysqli_fetch_assoc($result)) {
        // Überprüfen, ob das Passwort korrekt ist
        if (password_verify($password, $row['password'])) {
            // Administrator erfolgreich authentifiziert, Session starten und zum Dashboard weiterleiten
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'admin';
            header("Location: admin_panel.php");
            exit();
        } else {
            // Fehlermeldung für ungültige Anmeldeinformationen
            $error_message = "Ungültige Anmeldeinformationen für den Administrator.";
        }
    } else {
        // Fehlermeldung für ungültige Anmeldeinformationen
        $error_message = "Ungültige Anmeldeinformationen für den Administrator.";
    }

    // Verbindung zur Datenbank schließen
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Anmeldung</title>
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
            color: #444;
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

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="email"],
        input[type="password"],
        button[type="submit"] {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
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

        .auth-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .auth-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-size: 14px;
            flex: 1;
            margin: 0 5px;
        }

        .auth-buttons button:first-child {
            margin-left: 0;
        }

        .auth-buttons button:last-child {
            margin-right: 0;
        }

        .auth-buttons button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .auth-buttons button.register {
            background-color: #007BFF;
        }

        .auth-buttons button.register:hover {
            background-color: #0056b3;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Administrator Anmeldung</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="email" name="email" placeholder="E-Mail-Adresse" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button type="submit">Anmelden</button>
        </form>
        <?php if(isset($error_message)) { ?>
            <p><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
