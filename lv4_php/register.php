<?php
include('includes/db.php');
include('includes/auth.php');
include('includes/functions.php');

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$message = "";
if (isset($_POST['register'])) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    $username_error = validateUsername($username);
    if ($username_error !== true) {
        $errors[] = $username_error;
    }

    $password_error = validatePassword($password);
    if ($password_error !== true) {
        $errors[] = $password_error;
    }

    if ($password !== $confirm_password) {
        $errors[] = "Lozinke se ne podudaraju.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = displayError("Korisničko ime već postoji.");
        } else {
            $hashed_password = hashPassword($password);
            $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $message = displaySuccess("Račun je uspješno kreiran! Sada se možete prijaviti.");
                header("refresh:3;url=login.php");
            } else {
                $message = displayError("Greška pri kreiranju računa.");
            }
        }
    } else {
        $message = displayError(implode("<br>", $errors));
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Registracija - Filmovi</title>
</head>
<body>
    <header>
        <h1>Registracija</h1>
        <nav aria-label="Primarna navigacija">
            <ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="gallery.php">Slike</a></li>
                <li><a href="login.php">Prijava</a></li>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        <div class="container">
            <h1>Registracija</h1>

            <?php echo $message; ?>

            <form method="POST" action="register.php" class="auth-form">
                <div class="form-group">
                    <label for="username">Korisničko ime:</label>
                    <input type="text" id="username" name="username" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <small>Korisničko ime mora imati 3-50 znakova (slova, brojevi, donja crta)</small>
                </div>

                <div class="form-group">
                    <label for="password">Lozinka:</label>
                    <input type="password" id="password" name="password" required>
                    <small>Lozinka mora imati najmanje 6 znakova</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Potvrdite lozinku:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" name="register" class="btn-primary">Registriraj se</button>
            </form>

            <p>Već imate račun? <a href="login.php">Prijavite se</a></p>
        </div>
    </main>

    <style>
    .auth-form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .form-group small {
        display: block;
        margin-top: 3px;
        color: #666;
        font-size: 0.9em;
    }

    .btn-primary {
        background: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        width: 100%;
    }

    .btn-primary:hover {
        background: #218838;
    }
    </style>
</body>
</html>