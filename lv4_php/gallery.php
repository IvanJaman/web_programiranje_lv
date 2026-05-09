<?php
include('includes/db.php');
include('includes/auth.php');
include('includes/functions.php');

$message = "";
if (isset($_POST['rate_image']) && isLoggedIn()) {
    $image_id = (int)$_POST['image_id'];
    $rating = (int)$_POST['rating'];

    if (validateRating($rating) === true) {
        $existing_rating = getUserImageRating($conn, getCurrentUserId(), $image_id);

        if ($existing_rating !== null) {
            $stmt = $conn->prepare("UPDATE image_ratings SET rating = ?, rated_at = NOW() WHERE user_id = ? AND image_id = ?");
            $stmt->bind_param("iii", $rating, getCurrentUserId(), $image_id);
            $action = "ažurirana";
        } else {
            $stmt = $conn->prepare("INSERT INTO image_ratings (user_id, image_id, rating) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", getCurrentUserId(), $image_id, $rating);
            $action = "spremljena";
        }

        if ($stmt->execute()) {
            $message = displaySuccess("Vaša ocjena je $action!");
        } else {
            $message = displayError("Greška pri spremanju ocjene.");
        }
    } else {
        $message = displayError("Nevažeća ocjena.");
    }
}

$images = [];
$image_dir = __DIR__ . '/images/';

if (is_dir($image_dir)) {
    $files = scandir($image_dir);
    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $image_extensions)) {
                $stmt = $conn->prepare("SELECT * FROM images WHERE filename = ?");
                $stmt->bind_param("s", $file);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $db_image = $result->fetch_assoc();
                    $images[] = [
                        'id' => $db_image['id'],
                        'filename' => $file,
                        'title' => $db_image['title'] ?: $file,
                        'description' => $db_image['description'],
                        'path' => 'images/' . $file
                    ];
                } else {
                    $stmt = $conn->prepare("INSERT INTO images (filename, title, path) VALUES (?, ?, ?)");
                    $title = pathinfo($file, PATHINFO_FILENAME);
                    $path = 'images/' . $file;
                    $stmt->bind_param("sss", $file, $title, $path);
                    $stmt->execute();

                    $images[] = [
                        'id' => $conn->insert_id,
                        'filename' => $file,
                        'title' => $title,
                        'description' => '',
                        'path' => $path
                    ];
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style_slike.css">
    <link rel="stylesheet" href="css/slike.css">

    <title>Galerija slika</title>
</head>
<body>

<header>
    <h1>Galerija slika</h1>

    <div class="menu">
        <button id="menu-toggle" aria-expanded="false">☰ Izbornik</button>

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

<main>
    <section class="galerija">
        <?php echo $message; ?>

        <div class="img-gallery-magnific">
            <?php foreach ($images as $index => $img): ?>
                <div class="magnific-img">
                    <a href="#img-<?php echo $index; ?>">
                        <img src="<?php echo htmlspecialchars($img['path']); ?>"
                             alt="<?php echo htmlspecialchars($img['title']); ?>"
                             loading="lazy">
                    </a>
                    <figcaption><?php echo htmlspecialchars($img['title']); ?></figcaption>

                    <div class="image-rating">
                        <?php
                        $avg_rating = getAverageImageRating($conn, $img['id']);
                        echo displayStarRating($avg_rating);
                        ?>

                        <?php if (isLoggedIn()): ?>
                            <form method="POST" action="gallery.php" class="rating-form">
                                <input type="hidden" name="image_id" value="<?php echo $img['id']; ?>">
                                <label>Ocijenite:</label>
                                <select name="rating" required>
                                    <option value="">-- Odaberite --</option>
                                    <option value="1">1 zvjezdica</option>
                                    <option value="2">2 zvjezdice</option>
                                    <option value="3">3 zvjezdice</option>
                                    <option value="4">4 zvjezdice</option>
                                    <option value="5">5 zvjezdica</option>
                                </select>
                                <button type="submit" name="rate_image">Ocijeni</button>
                            </form>
                        <?php else: ?>
                            <p><a href="login.php">Prijavite se</a> da biste ocijenili sliku.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="img-<?php echo $index; ?>" class="lightbox">
                    <a href="#" class="lightbox-close">✕</a>
                    <img src="<?php echo htmlspecialchars($img['path']); ?>"
                         alt="<?php echo htmlspecialchars($img['title']); ?> enlarged">
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
.image-rating {
    margin-top: 10px;
    padding: 10px;
    background: #f9f9f9;
    border-radius: 5px;
    text-align: center;
}

.rating-form {
    margin-top: 10px;
}

.rating-form select, .rating-form button {
    margin: 5px;
    padding: 5px;
}

.stars {
    font-size: 18px;
    color: #ffd700;
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

    const lightboxLinks = document.querySelectorAll('.magnific-img a[href^="#"]');
    const lightboxes = document.querySelectorAll('.lightbox');

    lightboxLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const lightbox = document.getElementById(targetId);
            if (lightbox) {
                lightbox.style.display = 'flex';
            }
        });
    });

    lightboxes.forEach(lightbox => {
        const closeBtn = lightbox.querySelector('.lightbox-close');
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            lightbox.style.display = 'none';
        });

        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                lightbox.style.display = 'none';
            }
        });
    });
});
</script>

</body>
</html>