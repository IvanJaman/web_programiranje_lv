<?php
// Helper functions for the application
// Validation, sanitization, and database queries

/**
 * Sanitize string input to prevent XSS attacks
 * @param string $input The input to sanitize
 * @return string Sanitized output
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate film title (not empty, max 255 chars)
 * @param string $title Film title
 * @return string|true Error message or true if valid
 */
function validateFilmTitle($title) {
    $title = trim($title);
    if (empty($title)) {
        return "Naslov filma je obavezan.";
    }
    if (strlen($title) > 255) {
        return "Naslov ne smije biti duži od 255 znakova.";
    }
    return true;
}

/**
 * Validate film year (valid year between 1800 and current year + 5)
 * @param int $year Year
 * @return string|true Error message or true if valid
 */
function validateFilmYear($year) {
    $year = (int)$year;
    $currentYear = date('Y');
    
    if ($year < 1800 || $year > $currentYear + 5) {
        return "Godina mora biti između 1800 i " . ($currentYear + 5) . ".";
    }
    return true;
}

/**
 * Validate film duration (in minutes, between 1 and 1000)
 * @param int $duration Duration in minutes
 * @return string|true Error message or true if valid
 */
function validateFilmDuration($duration) {
    $duration = (int)$duration;
    
    if ($duration < 1 || $duration > 1000) {
        return "Trajanje filma mora biti između 1 i 1000 minuta.";
    }
    return true;
}

/**
 * Validate film genre (not empty)
 * @param string $genre Genre
 * @return string|true Error message or true if valid
 */
function validateFilmGenre($genre) {
    $genre = trim($genre);
    if (empty($genre)) {
        return "Žanr je obavezan.";
    }
    if (strlen($genre) > 100) {
        return "Žanr ne smije biti duži od 100 znakova.";
    }
    return true;
}

/**
 * Validate country (not empty)
 * @param string $country Country
 * @return string|true Error message or true if valid
 */
function validateCountry($country) {
    $country = trim($country);
    if (empty($country)) {
        return "Zemlja je obavezna.";
    }
    if (strlen($country) > 100) {
        return "Zemlja ne smije biti duža od 100 znakova.";
    }
    return true;
}

/**
 * Validate rating (between 1 and 5)
 * @param int $rating Rating value
 * @return string|true Error message or true if valid
 */
function validateRating($rating) {
    $rating = (int)$rating;
    
    if ($rating < 1 || $rating > 5) {
        return "Ocjena mora biti između 1 i 5.";
    }
    return true;
}

/**
 * Validate username (3-50 characters, alphanumeric + underscore)
 * @param string $username Username
 * @return string|true Error message or true if valid
 */
function validateUsername($username) {
    $username = trim($username);
    
    if (strlen($username) < 3) {
        return "Korisničko ime mora imati najmanje 3 znaka.";
    }
    if (strlen($username) > 50) {
        return "Korisničko ime ne smije biti duže od 50 znakova.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "Korisničko ime može sadržavati samo postojeće znakove, brojeve i donje crtice.";
    }
    return true;
}

/**
 * Validate password (min 6 characters)
 * @param string $password Password
 * @return string|true Error message or true if valid
 */
function validatePassword($password) {
    if (strlen($password) < 6) {
        return "Lozinka mora imati najmanje 6 znakova.";
    }
    return true;
}

/**
 * Calculate average rating for a film
 * @param mysqli $conn Database connection
 * @param int $film_id Film ID
 * @return float Average rating (0 if no ratings)
 */
function getAverageFilmRating($conn, $film_id) {
    $stmt = $conn->prepare("SELECT AVG(ocjena) as avg_rating FROM film_ratings WHERE film_id = ?");
    $stmt->bind_param("i", $film_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['avg_rating'] ? round($row['avg_rating'], 1) : 0;
}

/**
 * Calculate average rating for an image
 * @param mysqli $conn Database connection
 * @param int $image_id Image ID
 * @return float Average rating (0 if no ratings)
 */
function getAverageImageRating($conn, $image_id) {
    $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM image_ratings WHERE image_id = ?");
    $stmt->bind_param("i", $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['avg_rating'] ? round($row['avg_rating'], 1) : 0;
}

/**
 * Get user's rating for a film (if exists)
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param int $film_id Film ID
 * @return int|null User's rating or null if not rated
 */
function getUserFilmRating($conn, $user_id, $film_id) {
    $stmt = $conn->prepare("SELECT ocjena FROM film_ratings WHERE user_id = ? AND film_id = ?");
    $stmt->bind_param("ii", $user_id, $film_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['ocjena'];
    }
    return null;
}

/**
 * Get user's rating for an image (if exists)
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param int $image_id Image ID
 * @return int|null User's rating or null if not rated
 */
function getUserImageRating($conn, $user_id, $image_id) {
    $stmt = $conn->prepare("SELECT rating FROM image_ratings WHERE user_id = ? AND image_id = ?");
    $stmt->bind_param("ii", $user_id, $image_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['rating'];
    }
    return null;
}

/**
 * Check if film is in user's desired list
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param int $film_id Film ID
 * @return bool True if film is in list, false otherwise
 */
function isFilmInDesiredList($conn, $user_id, $film_id) {
    $stmt = $conn->prepare("SELECT id FROM desired_films WHERE user_id = ? AND film_id = ?");
    $stmt->bind_param("ii", $user_id, $film_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

/**
 * Display a success message with HTML styling
 * @param string $message Success message
 * @return string HTML alert box
 */
function displaySuccess($message) {
    return '<div class="alert alert-success">' . sanitize($message) . '</div>';
}

/**
 * Display an error message with HTML styling
 * @param string $message Error message
 * @return string HTML alert box
 */
function displayError($message) {
    return '<div class="alert alert-danger">' . sanitize($message) . '</div>';
}

/**
 * Display a warning message with HTML styling
 * @param string $message Warning message
 * @return string HTML alert box
 */
function displayWarning($message) {
    return '<div class="alert alert-warning">' . sanitize($message) . '</div>';
}

/**
 * Generate star rating HTML (1-5 stars)
 * @param float $rating Average rating
 * @param bool $interactive Whether stars should be clickable (for future enhancement)
 * @return string HTML with star representation
 */
function displayStarRating($rating, $interactive = false) {
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
    
    $html = '<span class="stars" title="' . round($rating, 1) . '/5">';
    
    // Full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $html .= '★';
    }
    
    // Half star
    if ($hasHalfStar) {
        $html .= '☆';
    }
    
    // Empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
        $html .= '☆';
    }
    
    $html .= ' (' . round($rating, 1) . ')</span>';
    
    return $html;
}
?>
