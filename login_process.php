<?php
session_start();

// Verbindung zur Datenbank herstellen
$connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

// Willkommensnachricht
function displayWelcomeMessage($email) {
    echo "<div class='notification success'>";
    echo "<h2 style='font-size: 28px; color: #333; margin-bottom: 10px;'>Willkommen zurück, $email!</h2>";
    echo "<p style='font-size: 18px; color: #333; line-height: 1.5;'>Schön, dass Sie wieder da sind. Viel Spaß beim Entdecken von CodeForge!</p>";
    echo "</div>";
}


// Fehlernachricht
function displayErrorMessage($message) {
    echo "<div class='notification error'>";
    echo "<p>$message</p>";
    echo "</div>";
}

// Wenn das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Daten aus dem Anmeldeformular abrufen
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL-Abfrage zum Überprüfen der Anmeldeinformationen
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($connection, $check_query);
    if (mysqli_num_rows($check_result) == 1) {
        $row = mysqli_fetch_assoc($check_result);
        if (password_verify($password, $row['password'])) {
            // Anmeldung erfolgreich, Benutzersitzung starten
            $_SESSION['email'] = $email;
            // Willkommensnachricht anzeigen
            displayWelcomeMessage($email);
            // Weiterleitung zur index.php
            header("refresh:3;url=index.php");
            exit();
        } else {
            displayErrorMessage("Falsches Passwort. Bitte versuchen Sie es erneut.");
        }
    } else {
        displayErrorMessage("Benutzer nicht gefunden. Bitte registrieren Sie sich zuerst.");
    }
}

// Verbindung zur Datenbank schließen
mysqli_close($connection);
?>

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

    .notification {
        text-align: center;
        padding: 20px;
        margin: 20px auto;
        border-radius: 10px;
        max-width: 400px;
        width: 90%;
        box-sizing: border-box;
        animation: fadeIn 0.5s ease-in-out;
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        position: relative;
        overflow: hidden;
    }

    .notification h2 {
        margin: 0;
        font-size: 24px;
        color: #fff;
    }

    .notification p {
        margin: 10px 0 0;
        font-size: 16px;
        color: #fff;
    }

    .notification.success {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
    }

    .notification.error {
        background: linear-gradient(135deg, #FF5733, #FF8D72);
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
