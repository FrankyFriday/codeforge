<?php
// Verbindung zur Datenbank herstellen
$connection = mysqli_connect("localhost", "root", "", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

// Schlüssel aus der URL abrufen
$key = $_GET['key'];

// Benutzer bestätigen, wenn der Schlüssel gültig ist
$update_query = "UPDATE users SET confirmed=1 WHERE confirmation_key='$key'";
if (mysqli_query($connection, $update_query)) {
    echo "Ihre Registrierung wurde erfolgreich bestätigt.";
} else {
    echo "Fehler beim Bestätigen der Registrierung: " . mysqli_error($connection);
}

// Verbindung zur Datenbank schließen
mysqli_close($connection);
?>
