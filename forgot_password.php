<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verbindung zur MySQL-Datenbank herstellen
    $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
    if (!$connection) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
    }

    // Überprüfen, ob die E-Mail-Adresse in der Datenbank existiert
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // E-Mail existiert, generiere Token
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 3600; // 1 Stunde gültig

        // Token in der Datenbank speichern
        $sql = "INSERT INTO password_resets (email, token, expires) VALUES ('$email', '$token', '$expires')";
        mysqli_query($connection, $sql);

        // Passwort zurücksetzen E-Mail senden
        $to = $email;
        $subject = "Passwort zurücksetzen bei CodeForge";
        $message = "Bitte klicken Sie auf den folgenden Link, um Ihr Passwort zurückzusetzen: \n";
        $message .= "http://localhost/reset_password.php?token=" . $token;
        $headers = "From: frankyfriday420@gmail.com";

        mail($to, $subject, $message, $headers);

        echo "Eine E-Mail zum Zurücksetzen des Passworts wurde gesendet, wenn die E-Mail-Adresse registriert ist.";
    } else {
        echo "Diese E-Mail-Adresse ist nicht registriert.";
    }

    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort vergessen</title>
    <style>
        /* Style für die Seite */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f2f2f2;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Passwort vergessen</h2>
        <form action="forgot_password.php" method="post">
            <input type="email" name="email" placeholder="E-Mail-Adresse" required />
            <button type="submit">Passwort zurücksetzen</button>
        </form>
    </div>
</body>
</html>
