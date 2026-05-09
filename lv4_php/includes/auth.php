<?php
// Authentication helper functions
// Handles sessions, login checks, and password operations

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Hash a password for secure storage
 * @param string $password The plain text password
 * @return string The hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify a plain text password against a hash
 * @param string $password The plain text password to check
 * @param string $hash The hashed password from database
 * @return bool True if password matches, false otherwise
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Check if user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if logged-in user is admin
 * @return bool True if user is admin, false otherwise
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirect to login page if not logged in
 * Used on pages that require authentication
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Redirect to login page if not admin
 * Used on admin-only pages
 */
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Log out the user
 * Clears all session data
 */
function logout() {
    $_SESSION = array();
    session_destroy();
    header("Location: index.php");
    exit();
}

/**
 * Get current user ID
 * @return int|null User ID if logged in, null otherwise
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get current username
 * @return string|null Username if logged in, null otherwise
 */
function getCurrentUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}
?>
