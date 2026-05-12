<?php
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);

    if ($stmt->execute()) {
        echo "Registracija uspješna!";
    } else {
        echo "Greška: korisnik već postoji.";
    }
}
?>

<form method="POST">
    <input name="username" placeholder="Korisničko ime" required>
    <input type="password" name="password" placeholder="Lozinka" required>
    <button>Registriraj se</button>
</form>