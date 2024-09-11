<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Verbindung zur MySQL-Datenbank herstellen
    $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
    if (!$connection) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
    }

    // Überprüfen, ob der Token gültig ist
    $sql = "SELECT * FROM password_resets WHERE token = '$token' AND expires >= " . date("U");
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];

        // Passwort in der Datenbank aktualisieren
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";
        mysqli_query($connection, $sql);

        // Token aus der Datenbank löschen
        $sql = "DELETE FROM password_resets WHERE token = '$token'";
        mysqli_query($connection, $sql);

        echo "Ihr Passwort wurde erfolgreich zurückgesetzt.";
    } else {
        echo "Der Link ist ungültig oder abgelaufen.";
    }

    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen</title>
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
        input[type="password"] {
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
        <h2>Passwort zurücksetzen</h2>
        <form action="reset_password.php" method="post">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" />
            <input type="password" name="password" placeholder="Neues Passwort" required />
            <button type="submit">Passwort zurücksetzen</button>
        </form>
    </div>
</body>
</html>
