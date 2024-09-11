<?php
session_start(); // Starte die Sitzung

// Überprüfe, ob der Benutzer angemeldet ist
if (!isset($_SESSION['email'])) {
    // Handle den Fall, dass der Benutzer nicht angemeldet ist
    // Zum Beispiel kannst du sie zur Login-Seite weiterleiten
    header("Location: login.php");
    exit(); // Beende das Skript
}

// Verbindung zur Datenbank herstellen
$connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

// Eingaben des Benutzers überprüfen und Passwort ändern
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    $old_password = mysqli_real_escape_string($connection, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($connection, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);

    // Überprüfen, ob das neue Passwort und die Bestätigung übereinstimmen
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Das neue Passwort stimmt nicht mit der Bestätigung überein.";
        header("Location: profil.php");
        exit();
    }

    // Passwort des Benutzers aus der Datenbank abrufen
    $sql = "SELECT password FROM users WHERE email=?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $hashed_password);
    mysqli_stmt_fetch($stmt);

    // Überprüfen, ob das alte Passwort korrekt ist
    if (password_verify($old_password, $hashed_password)) {
        // Neues Passwort hashen und in die Datenbank speichern
        $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE users SET password=? WHERE email=?";
        $update_stmt = mysqli_prepare($connection, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "ss", $new_hashed_password, $email);
        mysqli_stmt_execute($update_stmt);

        $_SESSION['success'] = "Passwort erfolgreich geändert.";
        header("Location: profil.php");
        exit();
    } else {
        $_SESSION['error'] = "Das alte Passwort ist falsch.";
        header("Location: profil.php");
        exit();
    }
}

// Datenbankverbindung schließen
mysqli_close($connection);
?>
