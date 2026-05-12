<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include("includes/db.php");

$user_id = $_SESSION["user_id"];
$image_id = $_POST["image_id"];
$rating = $_POST["rating"];

$stmt = $conn->prepare("
INSERT INTO image_ratings (user_id, image_id, rating)
VALUES (?, ?, ?)
ON DUPLICATE KEY UPDATE rating=?
");

$stmt->bind_param("iiii", $user_id, $image_id, $rating, $rating);
$stmt->execute();

header("Location: gallery.php");