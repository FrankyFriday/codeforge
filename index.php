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

// Profilbild abrufen
$sql = "SELECT profile_picture FROM users WHERE email = '$email'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
$profilePicture = $row['profile_picture'] ? $row['profile_picture'] : 'männchen.jpg';

// Funktion zum Extrahieren von Kommentaren aus dem Code
function extractComments($code, $email) {
    preg_match_all('/\/\/\s*'.preg_quote($email).'\s*:\s*(.*?)\n/', $code, $matches);
    return isset($matches[1]) ? $matches[1] : [];
}

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
    <title>CodeForge</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism-okaidia.min.css">
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            min-height: 100vh;
            color: #333;
        }

        header {
            background-color: #2C3E50;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 50px;
            height: 50px;
            cursor: pointer;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.1);
        }

        .auth-buttons button {
            padding: 10px 20px;
            border: none;
            background-color: #E74C3C;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .auth-buttons button:hover {
            background-color: #C0392B;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 900px;
            width: 100%;
            margin: 20px auto;
            box-sizing: border-box;
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
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

        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }

        #code-list {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        .code-item {
            background-color: #ffffff;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            transition: background-color 0.3s ease, max-height 0.5s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .code-item:hover {
            background-color: #F8F9FA;
        }

        .code-header {
            display: flex;
            justify-content: flex-end;
        }

        .view-more-button {
            background-color: #3498DB;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 10px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .view-more-button:hover {
            background-color: #2980B9;
        }

        .code-content {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: monospace;
        }

        .search-box {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-input {
            width: 70%;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
            margin-right: 10px;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #3498DB;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
        }

        .search-button {
            padding: 10px 20px;
            border: none;
            background-color: #3498DB;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #2980B9;
        }

        .rating {
            display: flex;
            align-items: center;
            margin: 10px 0;
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

        .comment-form button {
            padding: 10px 20px;
            border: none;
            background-color: #E74C3C; /* gleiche Farbe wie der Logout-Button */
            color: white;
            border-radius: 20px; /* gleiche Rundung wie der Logout-Button */
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px; /* optional, um etwas Abstand nach oben zu schaffen */
        }

        .comment-form button:hover {
            background-color: #C0392B; /* gleiche Hover-Farbe wie der Logout-Button */
        }


        .comment-form {
            margin-top: 20px;
        }

        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        .comment {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .comment-section {
            margin-top: 20px;
            background-color: #f7f9fc;
            padding: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <header>
        <img src="<?php echo $profilePicture; ?>" alt="Profilbild" class="logo" onclick="location.href='profil.php';">
        <h1>CodeForge</h1>
        <div class="auth-buttons">
            <button type="button" class="logout-button" onclick="location.href='logout.php';">Ausloggen</button>
            <button type="button" class="admin-panel" onclick="location.href='admin_login.php';">Admin-Panel</button> 
        </div>
    </header>

    <div class="container">
        <div class="search-box">
            <input type="text" id="search-input" class="search-input" placeholder="Suche nach einer Programmiersprache">
            <button onclick="searchCodes()" class="search-button">Suchen</button>
        </div>
        <ul id="code-list">
           <?php
            // SQL-Abfrage zum Abrufen der Codes aus der Datenbank
            $sql = "SELECT * FROM snippets";
            $result = mysqli_query($connection, $sql);

            // Daten in HTML-Liste anzeigen
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $snippetId = $row['id'];
                    // Kommentare extrahieren
                    $comments = extractComments($row['code'], $email);
                    $commentHTML = '';
                    foreach ($comments as $comment) {
                        $commentHTML .= "<span style='color: #999;'>//$email: $comment</span><br>";
                    }
                    // Bewertungen und Kommentare abrufen
                    $rating = getRatings($snippetId, $connection);
                    $snippetComments = getComments($snippetId, $connection);
                    
                    // HTML für den Codeeintrag erstellen
                    echo "<li class='code-item'>
                        <div class='code-header'>
                            <a href='code_view.php?id=$snippetId' class='view-more-button'>Mehr anzeigen</a>
                        </div>
                        <div class='code-content'>
                            <strong>" . htmlspecialchars($row['language']) . "</strong>:<br>$commentHTML" . htmlspecialchars($row['code']) . "
                        </div>
                        <div class='rating'>
                            <span>Bewertung: </span>
                            <span class='rating-star' onclick='rateCode($snippetId, 1)'>⭐</span>
                            <span class='rating-star' onclick='rateCode($snippetId, 2)'>⭐</span>
                            <span class='rating-star' onclick='rateCode($snippetId, 3)'>⭐</span>
                            <span class='rating-star' onclick='rateCode($snippetId, 4)'>⭐</span>
                            <span class='rating-star' onclick='rateCode($snippetId, 5)'>⭐</span>
                            <span id='rating_$snippetId'>$rating</span>
                        </div>
                        <div class='comment-section'>
                            <form class='comment-form' onsubmit='addComment(event, $snippetId)'>
                                <textarea placeholder='Kommentar hinzufügen...'></textarea>
                                <button type='submit'>Kommentar hinzufügen</button>
                            </form>
                            <div class='comments'>";
                                foreach ($snippetComments as $comment) {
                                    echo "<div class='comment'>
                                        <strong>" . htmlspecialchars($comment['email']) . "</strong>: " . htmlspecialchars($comment['comment']) . "
                                    </div>";
                                }
                            echo "</div>
                        </div>
                    </li>";
                }
            } else {
                echo "Keine Codes gefunden.";
            }

            mysqli_close($connection);
            ?>
        </ul>
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

        function searchCodes() {
            var query = document.getElementById('search-input').value.toLowerCase();
            var items = document.querySelectorAll('#code-list .code-item');
            items.forEach(function(item) {
                var language = item.querySelector('.code-content strong').textContent.toLowerCase();
                if (language.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
