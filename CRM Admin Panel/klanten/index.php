<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

$query = "SELECT * FROM klanten ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Klantenoverzicht</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <h1>Klantenoverzicht</h1>

    <a href="create.php">Klant toevoegen</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bedrijfsnaam</th>
                <th>Contactpersoon</th>
                <th>Email</th>
                <th>Telefoon</th>
                <th>Status</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['bedrijfsnaam']); ?></td>
                        <td><?= htmlspecialchars($row['contactpersoon']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['telefoon']); ?></td>
                        <td><?= $row['status']; ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id']; ?>">Bewerken</a> |
                            <a href="delete.php?id=<?= $row['id']; ?>"
                                onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">
                                Verwijderen
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Geen klanten gevonden</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>