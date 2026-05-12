<?php
session_start();
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        if (password_verify($password, $user["password_hash"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: index.php");
            exit;
        } else {
            echo "Pogrešna lozinka!";
        }

    } else {
        echo "Korisnik ne postoji!";
    }
}
?>

<form method="POST">
    <input name="username" placeholder="Korisničko ime" required>
    <input type="password" name="password" placeholder="Lozinka" required>
    <button>Prijava</button>
</form>