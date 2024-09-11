<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $role = mysqli_real_escape_string($connection, $_POST['role']);

    // Passwort verschlüsseln
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $insert_sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $insert_sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $hashed_password, $role);
    mysqli_stmt_execute($stmt);

    header("Location: user_management.php");
    exit();
}
?>