<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST["film_id"])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$film_id = intval($_POST["film_id"]);

$stmt = $conn->prepare("
    DELETE FROM desired_films
    WHERE user_id = ? AND film_id = ?
");

$stmt->bind_param("ii", $user_id, $film_id);
$stmt->execute();

header("Location: cart.php");
exit;
?>