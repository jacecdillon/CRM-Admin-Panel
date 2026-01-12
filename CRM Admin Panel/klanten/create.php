<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bedrijfsnaam     = trim($_POST["bedrijfsnaam"]);
    $contactpersoon  = trim($_POST["contactpersoon"]);
    $email            = trim($_POST["email"]);
    $telefoon         = trim($_POST["telefoon"]);
    $status           = $_POST["status"];

    if (empty($bedrijfsnaam) || empty($contactpersoon) || empty($email)) {
        $error = "Vul alle verplichte velden in.";
    } else {
        $query = "INSERT INTO klanten (bedrijfsnaam, contactpersoon, email, telefoon, status)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $bedrijfsnaam,
            $contactpersoon,
            $email,
            $telefoon,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Er is iets misgegaan bij het opslaan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Klant toevoegen</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h1>Klant toevoegen</h1>

    <a href="index.php">⬅ Terug naar overzicht</a>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <label>
            Bedrijfsnaam*<br>
            <input type="text" name="bedrijfsnaam" required>
        </label><br><br>

        <label>
            Contactpersoon*<br>
            <input type="text" name="contactpersoon" required>
        </label><br><br>

        <label>
            Email*<br>
            <input type="email" name="email" required>
        </label><br><br>

        <label>
            Telefoon<br>
            <input type="text" name="telefoon">
        </label><br><br>

        <label>
            Status<br>
            <select name="status">
                <option value="Actief">Actief</option>
                <option value="Inactief">Inactief</option>
            </select>
        </label><br><br>

        <button type="submit">Opslaan</button>
    </form>

</body>

</html>