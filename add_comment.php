<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    exit();
}

$snippetId = isset($_POST['snippet_id']) ? intval($_POST['snippet_id']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$email = $_SESSION['email'];

if ($snippetId > 0 && !empty($comment)) {
    $connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
    if (!$connection) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
    }

    $comment = mysqli_real_escape_string($connection, $comment);
    $sql = "INSERT INTO comments (snippet_id, email, comment, created_at) VALUES ($snippetId, '$email', '$comment', NOW())";

    if (mysqli_query($connection, $sql)) {
        $sql = "SELECT * FROM comments WHERE snippet_id = $snippetId ORDER BY created_at DESC";
        $result = mysqli_query($connection, $sql);
        $comments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
        foreach ($comments as $comment) {
            echo "<div class='comment'>
                <strong>" . htmlspecialchars($comment['email']) . "</strong>: " . htmlspecialchars($comment['comment']) . "
            </div>";
        }
    } else {
        http_response_code(500);
    }

    mysqli_close($connection);
}
?>
