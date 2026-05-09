<?php
include('includes/db.php');
include('includes/auth.php');
include('includes/functions.php');

$where_clauses = [];
$params = [];
$types = "";

if (isset($_GET['žanr']) && !empty($_GET['žanr'])) {
    $where_clauses[] = "žanr = ?";
    $params[] = $_GET['žanr'];
    $types .= "s";
}

if (isset($_GET['godina']) && !empty($_GET['godina'])) {
    $where_clauses[] = "godina >= ?";
    $params[] = (int)$_GET['godina'];
    $types .= "i";
}

if (isset($_GET['zemlja']) && !empty($_GET['zemlja'])) {
    $where_clauses[] = "zemlja_porijekla = ?";
    $params[] = $_GET['zemlja'];
    $types .= "s";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

$sql = "SELECT * FROM films $where_sql ORDER BY naslov";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$films = $result->fetch_all(MYSQLI_ASSOC);

$message = "";
if (isset($_POST['add_to_videoteka']) && isLoggedIn()) {
    $film_id = (int)$_POST['film_id'];

    if (isFilmInDesiredList($conn, getCurrentUserId(), $film_id)) {
        $message = displayError("Ovaj film je već u vašoj videoteci!");
    } else {
        $avg_rating = getAverageFilmRating($conn, $film_id);

        if ($avg_rating > 0 && $avg_rating < 5.0) {
            $message = displayWarning("Ovaj film ima nisku prosječnu ocjenu (" . number_format($avg_rating, 1) . "/10). Jeste li sigurni da ga želite dodati?");
        }

        $stmt = $conn->prepare("INSERT INTO desired_films (user_id, film_id) VALUES (?, ?)");
        $stmt->bind_param("ii", getCurrentUserId(), $film_id);

        if ($stmt->execute()) {
            $message = displaySuccess("Film je dodan u vašu videoteka!");
        } else {
            $message = displayError("Greška pri dodavanju filma.");
        }
    }
}

$genres_result = $conn->query("SELECT DISTINCT žanr FROM films ORDER BY žanr");
$genres = $genres_result->fetch_all(MYSQLI_ASSOC);

$countries_result = $conn->query("SELECT DISTINCT zemlja_porijekla FROM films ORDER BY zemlja_porijekla");
$countries = $countries_result->fetch_all(MYSQLI_ASSOC);
?>

<?php include('includes/header.php'); ?>

<main id="main-content">
    <h1>Popis Filmova</h1>

    <?php echo $message; ?>

    <div class="container">
        <section id="filteri">
            <h2>Filtriranje</h2>

            <form method="GET" action="index.php">
                <select name="žanr" id="filter-žanr">
                    <option value="">Svi žanrovi</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?php echo htmlspecialchars($genre['žanr']); ?>"
                                <?php echo (isset($_GET['žanr']) && $_GET['žanr'] === $genre['žanr']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genre['žanr']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <input type="number" name="godina" id="filter-godina"
                       placeholder="Godina od (npr. 2000)"
                       value="<?php echo isset($_GET['godina']) ? htmlspecialchars($_GET['godina']) : ''; ?>">

                <select name="zemlja" id="filter-zemlja">
                    <option value="">Sve zemlje</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo htmlspecialchars($country['zemlja_porijekla']); ?>"
                                <?php echo (isset($_GET['zemlja']) && $_GET['zemlja'] === $country['zemlja_porijekla']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($country['zemlja_porijekla']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" id="filter-btn">Filtriraj</button>
                <a href="index.php"><button type="button">Poništi filtere</button></a>
            </form>
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
                            <?php if (isLoggedIn()): ?>
                                <th>Akcije</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="film-table-body">
                        <?php foreach ($films as $film): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($film['naslov']); ?></td>
                                <td><?php echo htmlspecialchars($film['godina']); ?></td>
                                <td><?php echo htmlspecialchars($film['žanr']); ?></td>
                                <td><?php echo htmlspecialchars($film['trajanje']); ?> min</td>
                                <td><?php echo displayStarRating(getAverageFilmRating($conn, $film['id'])); ?></td>
                                <td><?php echo htmlspecialchars($film['redatelj']); ?></td>
                                <td><?php echo htmlspecialchars($film['zemlja_porijekla']); ?></td>
                                <?php if (isLoggedIn()): ?>
                                    <td>
                                        <form method="POST" action="index.php" style="display: inline;">
                                            <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                                            <button type="submit" name="add_to_videoteka"
                                                    onclick="return confirm('Dodati film u videoteka?')">
                                                Dodaj u videoteka
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="toggle-box">
                    <button class="toggle-btn">Prikaži više</button>

                    <div class="hidden-content">
                        <p>Ova stranica prikazuje popis filmova s osnovnim informacijama kao što su godina, žanr i ocjena.</p>
                        <p>Podaci se učitavaju iz MySQL baze podataka i mogu se filtrirati prema različitim kriterijima.</p>
                        <?php if (!isLoggedIn()): ?>
                            <p><a href="login.php">Prijavite se</a> da biste dodavali filmove u svoju osobnu videoteka.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <aside>
                <h2>Istaknuto</h2>
                <p>Ovdje će biti prikazani istaknuti filmovi ili najnoviji dodani filmovi.</p>

                <?php if (isLoggedIn()): ?>
                    <h3>Vaša videoteka</h3>
                    <p>Imate <?php
                        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM desired_films WHERE user_id = ?");
                        $stmt->bind_param("i", getCurrentUserId());
                        $stmt->execute();
                        $count_result = $stmt->get_result();
                        $count = $count_result->fetch_assoc()['count'];
                        echo $count;
                    ?> filmova u videoteci.</p>
                    <a href="moja_videoteka.php">Pregledaj videoteka</a>
                <?php else: ?>
                    <p><a href="login.php">Prijavite se</a> da biste kreirali svoju videoteka.</p>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const nav = document.querySelector('nav');

    menuToggle.addEventListener('click', function() {
        const expanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !expanded);
        nav.classList.toggle('open');
    });

    const kosaricaBtn = document.getElementById('kosarica-btn');
    if (kosaricaBtn) {
        kosaricaBtn.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            document.getElementById('kosarica-dropdown').classList.toggle('hidden');
        });
    }

    const toggleBtn = document.querySelector('.toggle-btn');
    const hiddenContent = document.querySelector('.hidden-content');

    toggleBtn.addEventListener('click', function() {
        hiddenContent.classList.toggle('hidden');
        this.textContent = hiddenContent.classList.contains('hidden') ? 'Prikaži više' : 'Sakrij';
    });
});
</script>

</body>
</html>