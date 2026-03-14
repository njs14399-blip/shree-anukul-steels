<?php
/**
 * Admin Logout
 */
require_once __DIR__ . '/../includes/config.php';

session_destroy();
header('Location: ' . SITE_URL . '/website/login.php');
exit;
