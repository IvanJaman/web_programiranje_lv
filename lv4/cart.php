<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT f.id, f.naslov, f.godina, f.zanr, f. trajanje, f.ocjena, f.redatelj, f.zemlja
    FROM films f
    INNER JOIN desired_films df ON f.id = df.film_id
    WHERE df.user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Moja videotekа</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<h1>Moja videotekа</h1>

<a href="index.php">⬅ Natrag</a>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Naslov</th>
            <th>Godina</th>
            <th>Žanr</th>
            <th>Trajanje</th>
            <th>Ocjena</th>
            <th>Redatelj</th>
            <th>Zemlja porijekla</th>
            <th>Akcija</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($result->num_rows === 0): ?>
            <tr>
                <td colspan="8">Nema dodanih filmova</td>
            </tr>
        <?php endif; ?>

        <?php while ($film = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $film['naslov'] ?></td>
                <td><?= $film['godina'] ?></td>
                <td><?= $film['zanr'] ?></td>
                <td><?= $film['trajanje'] ?></td>
                <td><?= $film['ocjena'] ?></td>
                <td><?= $film['redatelj'] ?></td>
                <td><?= $film['zemlja'] ?></td>
                <td>
                    <form method="POST" action="remove_from_cart.php">
                        <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                        <button>Ukloni</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>