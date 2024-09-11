<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $language = $_POST['language'];

    if (!empty($code) && !empty($language)) {
        $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");

        if (!$connection) {
            die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
        }

        $stmt = $connection->prepare("INSERT INTO snippets (language, code) VALUES (?, ?)");
        $stmt->bind_param("ss", $language, $code);

        if ($stmt->execute()) {
            echo "Code erfolgreich hochgeladen!";
        } else {
            echo "Fehler beim Hochladen des Codes: " . $stmt->error;
        }

        $stmt->close();
        mysqli_close($connection);
    } else {
        echo "Bitte füllen Sie alle Felder aus.";
    }
} else {
    echo "Ungültige Anforderung.";
}
?>
