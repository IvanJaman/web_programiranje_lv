<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST["film_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$film_id = intval($_POST["film_id"]);


$stmt = $conn->prepare("SELECT id FROM films WHERE id = ?");
$stmt->bind_param("i", $film_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO desired_films (user_id, film_id)
    VALUES (?, ?)
");

$stmt->bind_param("ii", $user_id, $film_id);

try {
    $stmt->execute();
} catch (Exception $e) {}

header("Location: index.php");
exit;
?>