<?php
/**
 * Shree Anukul Steels - Configuration File
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Site configuration
define('SITE_NAME', 'Shree Anukul Steels');
define('SITE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define('BASE_PATH', dirname(__DIR__));
define('DB_PATH', BASE_PATH . '/database/steels.db');
define('UPLOAD_PATH', BASE_PATH . '/website/uploads/');
define('ADMIN_PATH', '/website');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Database connection
function getDB() {
    static $db = null;
    if ($db === null) {
        try {
            $db = new PDO('sqlite:' . DB_PATH);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->exec('PRAGMA journal_mode=WAL');
            $db->exec('PRAGMA foreign_keys=ON');
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $db;
}

// Get website setting
function getSetting($key, $default = '') {
    try {
        $db = getDB();
        $stmt = $db->prepare('SELECT setting_value FROM website_settings WHERE setting_key = ?');
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

// Get SEO settings for a page
function getSEO($page) {
    try {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM seo_settings WHERE page_name = ?');
        $stmt->execute([$page]);
        return $stmt->fetch() ?: [];
    } catch (Exception $e) {
        return [];
    }
}

// Sanitize input
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Generate slug
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect function
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// JSON response
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
