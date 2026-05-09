<?php
// Include necessary files
include('includes/db.php');
include('includes/auth.php');
include('includes/functions.php');

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Handle login form submission
$message = "";
if (isset($_POST['login'])) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        $message = displayError("Molimo unesite korisničko ime i lozinku.");
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (verifyPassword($password, $user['password_hash'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];

                $message = displaySuccess("Uspješno ste prijavljeni!");
                header("refresh:2;url=index.php");
            } else {
                $message = displayError("Neispravna lozinka.");
            }
        } else {
            $message = displayError("Korisničko ime ne postoji.");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Prijava - Filmovi</title>
</head>
<body>
    <header>
        <h1>Prijava</h1>
        <nav aria-label="Primarna navigacija">
            <ul>
                <li><a href="index.php">Početna</a></li>
                <li><a href="gallery.php">Slike</a></li>
                <li><a href="register.php">Registracija</a></li>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        <div class="container">
            <h1>Prijava</h1>

            <?php echo $message; ?>

            <form method="POST" action="login.php" class="auth-form">
                <div class="form-group">
                    <label for="username">Korisničko ime:</label>
                    <input type="text" id="username" name="username" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Lozinka:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" name="login" class="btn-primary">Prijavi se</button>
            </form>

            <p>Nemate račun? <a href="register.php">Registrirajte se</a></p>
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

    .btn-primary {
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        width: 100%;
    }

    .btn-primary:hover {
        background: #0056b3;
    }
    </style>
</body>
</html>