<?php
session_start();

// Verbindung zur MySQL-Datenbank herstellen
$connection = mysqli_connect("127.0.0.1", "root", "", "codeforge");
if (!$connection) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Passwort verschlüsseln
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $insert_sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insert_sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $hashed_password, $role);
    if(mysqli_stmt_execute($stmt)) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Fehler beim Hinzufügen des Benutzers: " . mysqli_error($connection);
    }
}

// Überprüfen, ob der Administrator angemeldet ist
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Benutzer löschen
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $delete_sql = "DELETE FROM users WHERE id=?";
    $stmt = mysqli_prepare($connection, $delete_sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if(mysqli_stmt_execute($stmt)) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Fehler beim Löschen des Benutzers: " . mysqli_error($connection);
    }
}

// Benutzerrolle ändern
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    $update_sql = "UPDATE users SET role=? WHERE id=?";
    $stmt = mysqli_prepare($connection, $update_sql);
    mysqli_stmt_bind_param($stmt, "si", $new_role, $user_id);
    if(mysqli_stmt_execute($stmt)) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Fehler beim Aktualisieren der Benutzerrolle: " . mysqli_error($connection);
    }
}

// Benutzer abrufen
$sql = "SELECT id, email, role FROM users";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benutzerverwaltung</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #444;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-size: 1.1em;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        form {
            margin-bottom: 20px;
            text-align: left;
        }

        form h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        select,
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            box-sizing: border-box;
            font-size: 1em;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus,
        select:focus {
            border-color: #a777e3;
            box-shadow: 0 0 8px rgba(167, 119, 227, 0.3);
            outline: none;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Benutzerverwaltung</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>E-Mail</th>
                <th>Rolle</th>
                <th>Aktionen</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <form action="user_management.php" method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <select name="role" onchange="this.form.submit()">
                                <option value="user" <?php if ($row['role'] == 'user') echo 'selected'; ?>>user</option>
                                <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>admin</option>
                            </select>
                            <input type="hidden" name="update_role" value="1">
                        </form>
                    </td>
                    <td>
                        <a href="user_management.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?');">Löschen</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <form method="post">
            <h2>Neuen Benutzer hinzufügen</h2>
            <input type="email" name="email" placeholder="E-Mail-Adresse" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <select name="role" required>
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
            <button type="submit">Benutzer hinzufügen</button>
        </form>
        <p><a href="admin_panel.php" class="back-link">Zurück zum Admin Panel</a></p>
    </div>
</body>
</html>

<?php
mysqli_close($connection);
?>
