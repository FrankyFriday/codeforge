<?php
session_start(); // Starte die Sitzung

// Beende die Sitzung
session_unset(); // Lösche alle Sitzungsvariablen
session_destroy(); // Zerstöre die Sitzung

// Leite den Benutzer zur Login-Seite weiter oder zu einer anderen Seite
header("Location: login.php");
exit(); // Beende das Skript
?>

