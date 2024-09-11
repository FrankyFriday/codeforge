<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin-Panel</title>
    <link rel="icon" type="image/x-icon" href="logo.jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #444;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            padding: 20px;
            text-align: center;
            margin-bottom: 40px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .dashboard-item {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        }

        .dashboard-item h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.8em;
        }

        .dashboard-item p {
            color: #666;
            margin-bottom: 20px;
        }

        .dashboard-item button {
            background-color: #a777e3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .dashboard-item button:hover {
            background-color: #9057c9;
            transform: translateY(-2px);
        }

        .form-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 40px;
            text-align: center;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="email"],
        input[type="password"],
        button[type="submit"] {
            width: 100%;
            padding: 15px;
            border-radius: 30px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            font-size: 1em;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #a777e3;
            box-shadow: 0 0 8px rgba(167, 119, 227, 0.3);
        }

        button[type="submit"] {
            background-color: #a777e3;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #9057c9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin-Panel</h1>
        </div>

        <div class="dashboard">
            <div class="dashboard-item">
                <h2>Benutzer verwalten</h2>
                <p>Hier kannst du Benutzer anzeigen, bearbeiten oder löschen.</p>
                <button onclick="location.href='user_management.php';">Zum Benutzermanagement</button>
            </div>
            <div class="dashboard-item">
                <h2>Statistiken</h2>
                <p>Zeige wichtige Statistiken und Metriken an.</p>
                <button onclick="location.href='statistics.php';">Zu den Statistiken</button>
            </div>
            <div class="dashboard-item">
                <h2>Logout</h2>
                <p>Melde dich vom Admin-Panel ab.</p>
                <button onclick="location.href='logout.php';">Logout</button>
            </div>
        </div>


    </div>
</body>
</html>
