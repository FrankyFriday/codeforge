<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung bei CodeForge</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f06, #ffeb3b);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff6f61;
            box-shadow: 0 0 8px rgba(255, 111, 97, 0.3);
        }

        button[type="submit"],
        button[type="button"] {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            border: none;
            margin-bottom: 10px;
            box-sizing: border-box;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            font-weight: bold;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            font-size: 14px;
            color: #333;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrierung bei CodeForge</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="email" name="email" placeholder="E-Mail-Adresse" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button type="submit">Registrieren</button>
            <button type="button" onclick="location.href='http://localhost/codeforge/login.php';">Login</button>
        </form>
        
        <?php
        $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");

        if (!$connection) {
            die("<p class='message error'>Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error() . "</p>");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $check_query = "SELECT * FROM users WHERE email='$email'";
            $check_result = mysqli_query($connection, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<p class='message error'>Die E-Mail-Adresse ist bereits registriert.</p>";
            } else {
                $insert_query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

                if (mysqli_query($connection, $insert_query)) {
                    echo "<p class='message success'>Vielen Dank für die Registrierung!</p>";
                    echo "<script>window.location.href = 'http://localhost/codeforge/login.php';</script>";
                } else {
                    echo "<p class='message error'>Fehler beim Registrieren des Benutzers: " . mysqli_error($connection) . "</p>";
                }
            }
        }

        mysqli_close($connection);
        ?>
    </div>
</body>
</html>
