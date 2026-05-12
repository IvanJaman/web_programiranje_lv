<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$images = $conn->query("SELECT * FROM images");
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Galerija slika</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>

<header>
    <h1>Galerija slika</h1>
    <p>Dobrodošao, <?= $_SESSION["username"] ?></p>

    <nav>
        <a href="index.php">Filmovi</a>
        <a href="gallery.php">Galerija</a>
        <a href="logout.php">Odjava</a>
    </nav>
</header>

<main>

<div class="gallery">

<?php while ($img = $images->fetch_assoc()): ?>

    <?php
        $image_id = $img['id'];

        $stmt = $conn->prepare("
            SELECT AVG(rating) AS avg_rating
            FROM image_ratings
            WHERE image_id = ?
        ");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $avg = $stmt->get_result()->fetch_assoc()['avg_rating'];
    ?>

    <div class="image-box">

        <img src="images/<?= htmlspecialchars($img['filename']) ?>" width="250">

        <h3><?= htmlspecialchars($img['title']) ?></h3>

        <p>
            ⭐ Prosjek: 
            <?= $avg ? round($avg, 1) : "0.0" ?>
        </p>

        <form method="POST" action="rate_image.php">
            <input type="hidden" name="image_id" value="<?= $image_id ?>">

            <label>Ocijeni:</label>
            <select name="rating" required>
                <option value="1">1 ⭐</option>
                <option value="2">2 ⭐</option>
                <option value="3">3 ⭐</option>
                <option value="4">4 ⭐</option>
                <option value="5">5 ⭐</option>
            </select>

            <button type="submit">Pošalji</button>
        </form>

    </div>

<?php endwhile; ?>

</div>

</main>

</body>
</html>