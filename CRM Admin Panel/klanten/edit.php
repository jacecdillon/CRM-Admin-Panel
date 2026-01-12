<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

$error = "";
$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$query = "SELECT * FROM klanten WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$klant = mysqli_fetch_assoc($result);

if (!$klant) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bedrijfsnaam    = trim($_POST["bedrijfsnaam"]);
    $contactpersoon = trim($_POST["contactpersoon"]);
    $email           = trim($_POST["email"]);
    $telefoon        = trim($_POST["telefoon"]);
    $status          = $_POST["status"];

    if (empty($bedrijfsnaam) || empty($contactpersoon) || empty($email)) {
        $error = "Vul alle verplichte velden in.";
    } else {
        $update = "UPDATE klanten
                   SET bedrijfsnaam = ?, contactpersoon = ?, email = ?, telefoon = ?, status = ?
                   WHERE id = ?";

        $stmt = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $bedrijfsnaam,
            $contactpersoon,
            $email,
            $telefoon,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Opslaan mislukt.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Klant bewerken</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h1>Klant bewerken</h1>

    <a href="index.php">⬅ Terug naar overzicht</a>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="post">
        <label>
            Bedrijfsnaam*<br>
            <input type="text" name="bedrijfsnaam"
                value="<?= htmlspecialchars($klant['bedrijfsnaam']); ?>" required>
        </label><br><br>

        <label>
            Contactpersoon*<br>
            <input type="text" name="contactpersoon"
                value="<?= htmlspecialchars($klant['contactpersoon']); ?>" required>
        </label><br><br>

        <label>
            Email*<br>
            <input type="email" name="email"
                value="<?= htmlspecialchars($klant['email']); ?>" required>
        </label><br><br>

        <label>
            Telefoon<br>
            <input type="text" name="telefoon"
                value="<?= htmlspecialchars($klant['telefoon']); ?>">
        </label><br><br>

        <label>
            Status<br>
            <select name="status">
                <option value="Actief" <?= $klant['status'] === 'Actief' ? 'selected' : ''; ?>>
                    Actief
                </option>
                <option value="Inactief" <?= $klant['status'] === 'Inactief' ? 'selected' : ''; ?>>
                    Inactief
                </option>
            </select>
        </label><br><br>

        <button type="submit">Opslaan</button>
    </form>

</body>

</html>