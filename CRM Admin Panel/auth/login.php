<?php
session_start();
require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username && $password) {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: ../klanten/index.php");
            exit;
        } else {
            $error = "Ongeldige gebruikersnaam of wachtwoord.";
        }
    } else {
        $error = "Vul beide velden in.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h1>Login</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <label>
            Gebruikersnaam<br>
            <input type="text" name="username" required>
        </label><br><br>

        <label>
            Wachtwoord<br>
            <input type="password" name="password" required>
        </label><br><br>

        <button type="submit">Inloggen</button>
    </form>

</body>

</html>