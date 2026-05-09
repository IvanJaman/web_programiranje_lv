<?php
include('includes/db.php');
include('includes/auth.php');
include('includes/functions.php');

requireAdmin();

$message = "";

if (isset($_POST['add_film'])) {
    $naslov = sanitize($_POST['naslov']);
    $godina = (int)$_POST['godina'];
    $zanr = sanitize($_POST['zanr']);
    $trajanje = (int)$_POST['trajanje'];
    $redatelj = sanitize($_POST['redatelj']);
    $zemlja_porijekla = sanitize($_POST['zemlja_porijekla']);
    $opis = sanitize($_POST['opis']);

    $errors = [];
    if (validateFilmTitle($naslov) !== true) $errors[] = validateFilmTitle($naslov);
    if (validateFilmYear($godina) !== true) $errors[] = validateFilmYear($godina);
    if (validateFilmGenre($zanr) !== true) $errors[] = validateFilmGenre($zanr);
    if (validateFilmDuration($trajanje) !== true) $errors[] = validateFilmDuration($trajanje);
    if (validateCountry($zemlja_porijekla) !== true) $errors[] = validateCountry($zemlja_porijekla);

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO films (naslov, godina, zanr, trajanje, redatelj, zemlja_porijekla, opis) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisisss", $naslov, $godina, $zanr, $trajanje, $redatelj, $zemlja_porijekla, $opis);

        if ($stmt->execute()) {
            $message = displaySuccess("Film je uspješno dodan!");
        } else {
            $message = displayError("Greška pri dodavanju filma.");
        }
    } else {
        $message = displayError(implode("<br>", $errors));
    }
}

if (isset($_POST['update_film'])) {
    $film_id = (int)$_POST['film_id'];
    $naslov = sanitize($_POST['naslov']);
    $godina = (int)$_POST['godina'];
    $zanr = sanitize($_POST['zanr']);
    $trajanje = (int)$_POST['trajanje'];
    $redatelj = sanitize($_POST['redatelj']);
    $zemlja_porijekla = sanitize($_POST['zemlja_porijekla']);
    $opis = sanitize($_POST['opis']);

    $errors = [];
    if (validateFilmTitle($naslov) !== true) $errors[] = validateFilmTitle($naslov);
    if (validateFilmYear($godina) !== true) $errors[] = validateFilmYear($godina);
    if (validateFilmGenre($zanr) !== true) $errors[] = validateFilmGenre($zanr);
    if (validateFilmDuration($trajanje) !== true) $errors[] = validateFilmDuration($trajanje);
    if (validateCountry($zemlja_porijekla) !== true) $errors[] = validateCountry($zemlja_porijekla);

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE films SET naslov=?, godina=?, zanr=?, trajanje=?, redatelj=?, zemlja_porijekla=?, opis=? WHERE id=?");
        $stmt->bind_param("sisisssi", $naslov, $godina, $zanr, $trajanje, $redatelj, $zemlja_porijekla, $opis, $film_id);

        if ($stmt->execute()) {
            $message = displaySuccess("Film je uspješno ažuriran!");
        } else {
            $message = displayError("Greška pri ažuriranju filma.");
        }
    } else {
        $message = displayError(implode("<br>", $errors));
    }
}

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $film_id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM films WHERE id = ?");
    $stmt->bind_param("i", $film_id);

    if ($stmt->execute()) {
        $message = displaySuccess("Film je uspješno obrisan!");
    } else {
        $message = displayError("Greška pri brisanju filma.");
    }
}

$result = $conn->query("SELECT * FROM films ORDER BY naslov");
$films = $result->fetch_all(MYSQLI_ASSOC);

$edit_film = null;
if (isset($_GET['edit']) && isset($_GET['id'])) {
    $film_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM films WHERE id = ?");
    $stmt->bind_param("i", $film_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_film = $result->fetch_assoc();
}
?>

<?php include('includes/header.php'); ?>

<main id="main-content">
    <h1>Upravljanje filmovima</h1>

    <?php echo $message; ?>

    <div class="container">
        <section>
            <h2><?php echo $edit_film ? 'Uredi film' : 'Dodaj novi film'; ?></h2>

            <form method="POST" action="admin_films.php" class="film-form">
                <?php if ($edit_film): ?>
                    <input type="hidden" name="film_id" value="<?php echo $edit_film['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="naslov">Naslov:</label>
                        <input type="text" id="naslov" name="naslov" required
                               value="<?php echo $edit_film ? htmlspecialchars($edit_film['naslov']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="godina">Godina:</label>
                        <input type="number" id="godina" name="godina" required
                               value="<?php echo $edit_film ? htmlspecialchars($edit_film['godina']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="zanr">Žanr:</label>
                        <input type="text" id="zanr" name="zanr" required
                               value="<?php echo $edit_film ? htmlspecialchars($edit_film['zanr']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="trajanje">Trajanje (min):</label>
                        <input type="number" id="trajanje" name="trajanje" required
                               value="<?php echo $edit_film ? htmlspecialchars($edit_film['trajanje']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="redatelj">Redatelj:</label>
                        <input type="text" id="redatelj" name="redatelj" required
                               value="<?php echo $edit_film ? htmlspecialchars($edit_film['redatelj']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="zemlja_porijekla">Zemlja porijekla:</label>
                        <input type="text" id="zemlja_porijekla" name="zemlja_porijekla" required
                               value="<?php echo $edit_film ? htmlspecialchars($edit_film['zemlja_porijekla']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="opis">Opis:</label>
                    <textarea id="opis" name="opis" rows="3"><?php echo $edit_film ? htmlspecialchars($edit_film['opis']) : ''; ?></textarea>
                </div>

                <button type="submit" name="<?php echo $edit_film ? 'update_film' : 'add_film'; ?>" class="btn-primary">
                    <?php echo $edit_film ? 'Ažuriraj film' : 'Dodaj film'; ?>
                </button>

                <?php if ($edit_film): ?>
                    <a href="admin_films.php" class="btn-secondary">Odustani</a>
                <?php endif; ?>
            </form>
        </section>

        <section>
            <h2>Popis filmova</h2>

            <table id="filmovi-tablica">
                <thead>
                    <tr>
                        <th>Naslov</th>
                        <th>Godina</th>
                        <th>Žanr</th>
                        <th>Trajanje</th>
                        <th>Redatelj</th>
                        <th>Zemlja</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($films as $film): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($film['naslov']); ?></td>
                            <td><?php echo htmlspecialchars($film['godina']); ?></td>
                            <td><?php echo htmlspecialchars($film['zanr']); ?></td>
                            <td><?php echo htmlspecialchars($film['trajanje']); ?> min</td>
                            <td><?php echo htmlspecialchars($film['redatelj']); ?></td>
                            <td><?php echo htmlspecialchars($film['zemlja_porijekla']); ?></td>
                            <td>
                                <a href="admin_films.php?edit=1&id=<?php echo $film['id']; ?>" class="btn-edit">Uredi</a>
                                <a href="admin_films.php?delete=1&id=<?php echo $film['id']; ?>"
                                   onclick="return confirm('Jeste li sigurni da želite obrisati ovaj film?')"
                                   class="btn-delete">Obriši</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</main>

<style>
.film-form {
    max-width: none;
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #f9f9f9;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.form-row .form-group {
    flex: 1;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input, .form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.form-group textarea {
    resize: vertical;
}

.btn-primary {
    background: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-edit {
    background: #ffc107;
    color: black;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 3px;
    margin-right: 5px;
}

.btn-delete {
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 3px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const nav = document.querySelector('nav');

    menuToggle.addEventListener('click', function() {
        const expanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !expanded);
        nav.classList.toggle('open');
    });
});
</script>

</body>
</html>