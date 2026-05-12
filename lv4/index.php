<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include("includes/db.php");
?>

<!DOCTYPE html>
	<html lang="hr">
	<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- SEO meta tag: -->
        <meta name="description" content="Web stranica o filmovima s popisom i osnovnim informacijama o filmovima.">
        
        <!-- CSS file -->
        <link rel="stylesheet" href="styles/index.css">

        <title>Filmovi</title> 
    </head>
	<body>
        <a href="#main-content" class="skip-link">Preskoči na glavni sadržaj</a>
		<header>
            <p>Dobrodošao, <?php echo $_SESSION["username"]; ?>!</p>

            <div class="menu">
                <a href="cart.php" id="kosarica-btn" class="cart-link">🛒 Košarica</a>

                <button id="menu-toggle" aria-expanded="false">☰ Izbornik</button>

                <a href="logout.php "id="kosarica-btn">Odjava</a>

                <nav aria-label="Primarna navigacija">
                    <ul>
                        <li><a href="index.php">Početna</a></li>
                        <li><a href="grafikon.html">Grafikon</a></li>
                        <li><a href="gallery.php">Slike</a></li>
                    </ul>
                </nav>
            </div>
        </header>

		<main id="main-content">
            <h1>Popis Filmova</h1>

            <div class="container">
                <section>
                    <h2>Pretraživanje (uskoro)</h2>
                    <p>Filtriranje se obrađuje na serveru putem baze podataka.</p>
                </section>
                <div class="content-row">
                    <section>
                        <h2>Tablica filmova</h2>

                        <table id="filmovi-tablica">
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
                                <?php
                                $result = $conn->query("SELECT * FROM films");
                                if ($result->num_rows === 0) {
                                    echo "<tr><td colspan='8'>Nema filmova u bazi</td></tr>";
                                }
                                while($film = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $film['naslov'] ?></td>
                                        <td><?= $film['godina'] ?></td>
                                        <td><?= $film['zanr'] ?></td>
                                        <td><?= $film['trajanje'] ?></td>
                                        <td><?= $film['ocjena'] ?></td>
                                        <td><?= $film['redatelj'] ?></td>
                                        <td><?= $film['zemlja'] ?></td>
                                        <td>
                                            <form method="POST" action="add_to_cart.php">
                                                <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                                                <button>Dodaj u košaricu</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                        </table>

                        <div class="toggle-box">
                            <button class="toggle-btn">Prikaži više</button>

                            <div class="hidden-content">
                                <p>Ova stranica prikazuje popis filmova s osnovnim informacijama kao što su godina, žanr i ocjena.</p>
                                <p>Podaci su informativnog karaktera i služe za demonstraciju HTML i CSS tehnika.</p>
                            </div>
                        </div>
                    </section>

                    <aside> 
                        <h2>Istaknuto</h2>

                        <p><strong>Film tjedna:</strong> The Dark Knight (2008)</p>
                        <p>Žanr: Akcijski</p>
                        <p>Ocjena: ⭐ 9.1</p>

                        <img src="images/darkknight.jpg" alt="Plakat filma The Dark Knight" class="responsive-img featured-img">
                    </aside>
                </div>
            </div>

            <article>
                <h2>Najnovije vijesti</h2>
                <p>Ovdje se nalazi članak s važnim informacijama.</p>
            </article>
        </main>
		<footer>
		<p>&copy; 2025. Web Programiranje. Sva prava pridrzana.</p>
		</footer>
        <script src="scripts.js"></script>
	</body>
	</html>