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
$connection = mysqli_connect("195.128.103.52", "borg5", "berry-1234", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

// Abfrage zur Ermittlung der Gesamtanzahl von Benutzern
$user_count_query = "SELECT COUNT(*) AS total FROM users";
$user_count_result = mysqli_query($connection, $user_count_query);
$user_count_row = mysqli_fetch_assoc($user_count_result);
$user_count = $user_count_row['total'];

// Abfrage zum Abrufen der letzten 10 Einträge aus dem Aktivitätsprotokoll
$activity_log_query = "SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT 10";
$activity_log_result = mysqli_query($connection, $activity_log_query);

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiken</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #444;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .statistic-item {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .statistic-item:last-child {
            margin-bottom: 0;
        }

        .activity-log {
            margin-top: 30px;
        }

        .activity-log-item {
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Statistiken</h1>
        <div class="statistic-item">Gesamtanzahl Benutzer: <?php echo $user_count; ?></div>
        <div class="activity-log">
            <h2>Aktivitätslog (Letzten 10 Einträge)</h2>
            <?php while ($row = mysqli_fetch_assoc($activity_log_result)) { ?>
                <div class="activity-log-item">
                    <strong><?php echo $row['timestamp']; ?></strong>: <?php echo $row['activity']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>

<?php
// Datenbankverbindung schließen
mysqli_close($connection);
?>
