<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    exit();
}

$snippetId = isset($_POST['snippet_id']) ? intval($_POST['snippet_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$email = $_SESSION['email'];

if ($snippetId > 0 && $rating > 0 && $rating <= 5) {
    $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
    if (!$connection) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
    }

    // Prüfen, ob der Benutzer bereits bewertet hat
    $sql = "SELECT * FROM ratings WHERE snippet_id = $snippetId AND email = '$email'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) > 0) {
        $sql = "UPDATE ratings SET rating = $rating WHERE snippet_id = $snippetId AND email = '$email'";
    } else {
        $sql = "INSERT INTO ratings (snippet_id, email, rating) VALUES ($snippetId, '$email', $rating)";
    }

    if (mysqli_query($connection, $sql)) {
        $sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE snippet_id = $snippetId";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        echo round($row['avg_rating'], 1);
    } else {
        http_response_code(500);
    }

    mysqli_close($connection);
}
?>
