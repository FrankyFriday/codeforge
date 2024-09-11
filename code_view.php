<?php
session_start(); // Starte die Sitzung

// Überprüfe, ob der Benutzer angemeldet ist
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit(); // Beende das Skript
}

$email = $_SESSION['email'];

// Verbindung zur MySQL-Datenbank herstellen
$connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

$snippetId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM snippets WHERE id = $snippetId";
$result = mysqli_query($connection, $sql);
$snippet = mysqli_fetch_assoc($result);

if (!$snippet) {
    echo "Snippet nicht gefunden.";
    exit();
}

// Bewertungen und Kommentare abrufen
$rating = getRatings($snippetId, $connection);
$snippetComments = getComments($snippetId, $connection);

// Funktion zum Abrufen der Bewertungen
function getRatings($snippetId, $connection) {
    $sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE snippet_id = $snippetId";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['avg_rating'] ? round($row['avg_rating'], 1) : 0;
}

// Funktion zum Abrufen der Kommentare
function getComments($snippetId, $connection) {
    $sql = "SELECT * FROM comments WHERE snippet_id = $snippetId ORDER BY created_at DESC";
    $result = mysqli_query($connection, $sql);
    $comments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
    }
    return $comments;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeForge - Code Ansicht</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism-okaidia.min.css">
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 900px;
            margin: 20px auto;
            box-sizing: border-box;
            overflow: hidden;
        }

        h1 {
            color: #2C3E50;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .code-content {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: monospace;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            position: relative;
        }

        .copy-button {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            background-color: #3498DB;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .copy-button:hover {
            background-color: #2980B9;
        }

        .rating {
            margin: 20px 0;
        }

        .rating-star {
            cursor: pointer;
            font-size: 20px;
            color: #F1C40F;
            margin-right: 5px;
            transition: color 0.3s ease;
        }

        .rating-star:hover {
            color: #F39C12;
        }

        .comment-section {
            margin-top: 20px;
            background-color: #f7f9fc;
            padding: 10px;
            border-radius: 10px;
        }

        .comment {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Code-Snippet</h1>
        <div class="code-content">
            <div class="code-header"><?php echo htmlspecialchars($snippet['language']); ?></div>
            <?php echo htmlspecialchars($snippet['code']); ?>
        </div>
        <button class="copy-button" onclick="copyCode()">Code kopieren</button>
        <div class="rating">
            <span>Bewertung: <?php echo $rating; ?> ⭐</span>
        </div>
        <div class="comment-section">
            <?php
            foreach ($snippetComments as $comment) {
                echo "<div class='comment'>
                    <strong>" . htmlspecialchars($comment['email']) . "</strong>: " . htmlspecialchars($comment['comment']) . "
                </div>";
            }
            ?>
        </div>
    </div>

    <script>
        function copyCode() {
            var codeText = document.querySelector('.code-content').textContent.trim();
            var textarea = document.createElement('textarea');
            textarea.value = codeText;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Code wurde kopiert: ' + codeText);
        }
    </script>
</body>
</html>

<?php
mysqli_close($connection);
?>
