<?php
include('includes/db.php');
include('includes/auth.php');
include('includes/functions.php');

requireLogin();

$message = "";
if (isset($_POST['remove_from_videoteka'])) {
    $film_id = (int)$_POST['film_id'];

    $stmt = $conn->prepare("DELETE FROM desired_films WHERE user_id = ? AND film_id = ?");
    $stmt->bind_param("ii", getCurrentUserId(), $film_id);

    if ($stmt->execute()) {
        $message = displaySuccess("Film je uklonjen iz vaše videoteke!");
    } else {
        $message = displayError("Greška pri uklanjanju filma.");
    }
}

$stmt = $conn->prepare("
    SELECT f.*, df.added_at
    FROM films f
    JOIN desired_films df ON f.id = df.film_id
    WHERE df.user_id = ?
    ORDER BY df.added_at DESC
");
$stmt->bind_param("i", getCurrentUserId());
$stmt->execute();
$result = $stmt->get_result();
$desired_films = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php include('includes/header.php'); ?>

<main id="main-content">
    <h1>Moja Videoteka</h1>

    <?php echo $message; ?>

    <div class="container">
        <?php if (empty($desired_films)): ?>
            <p>Vaša videoteka je prazna. <a href="index.php">Dodajte filmove</a> iz kataloga.</p>
        <?php else: ?>
            <section>
                <h2>Vaši filmovi (<?php echo count($desired_films); ?>)</h2>

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
                            <th>Dodan</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($desired_films as $film): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($film['naslov']); ?></td>
                                <td><?php echo htmlspecialchars($film['godina']); ?></td>
                                <td><?php echo htmlspecialchars($film['zanr']); ?></td>
                                <td><?php echo htmlspecialchars($film['trajanje']); ?> min</td>
                                <td><?php echo displayStarRating(getAverageFilmRating($conn, $film['id'])); ?></td>
                                <td><?php echo htmlspecialchars($film['redatelj']); ?></td>
                                <td><?php echo htmlspecialchars($film['zemlja_porijekla']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($film['added_at'])); ?></td>
                                <td>
                                    <form method="POST" action="moja_videoteka.php" style="display: inline;">
                                        <input type="hidden" name="film_id" value="<?php echo $film['id']; ?>">
                                        <button type="submit" name="remove_from_videoteka"
                                                onclick="return confirm('Ukloniti film iz videoteke?')"
                                                style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                                            Ukloni
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
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
});
</script>

</body>
</html>