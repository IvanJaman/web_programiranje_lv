<?php
include_once('includes/auth.php');
include_once('includes/db.php');
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Web stranica o filmovima s popisom i osnovnim informacijama o filmovima.">

    <link rel="stylesheet" href="css/index.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>

    <title>Filmovi</title>
</head>
<body>
    <a href="#main-content" class="skip-link">Preskoči na glavni sadržaj</a>
    <header>
        <h1>Filmovi</h1>

        <div class="menu">
            <?php if (isLoggedIn()): ?>
                <button id="kosarica-btn" aria-expanded="false">🛒 Moja videoteka</button>
            <?php endif; ?>

            <button id="menu-toggle" aria-expanded="false">☰ Izbornik</button>

            <?php if (isLoggedIn()): ?>
                <div id="kosarica-dropdown" class="kosarica-dropdown hidden">
                    <h3>Moja videoteka</h3>
                    <ul id="kosarica-lista">
                    </ul>
                    <a href="moja_videoteka.php"><button>Pregledaj videoteku</button></a>
                </div>
            <?php endif; ?>

            <nav aria-label="Primarna navigacija">
                <ul>
                    <li><a href="index.php">Početna</a></li>
                    <li><a href="/grafikon">Grafikon</a></li>
                    <li><a href="gallery.php">Slike</a></li>
                    <?php if (!isLoggedIn()): ?>
                        <li><a href="login.php">Prijava</a></li>
                        <li><a href="register.php">Registracija</a></li>
                    <?php else: ?>
                        <li><a href="logout.php">Odjava (<?php echo htmlspecialchars(getCurrentUsername()); ?>)</a></li>
                        <?php if (isAdmin()): ?>
                            <li><a href="admin_films.php">Upravljanje filmovima</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>