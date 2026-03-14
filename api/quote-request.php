<?php
/**
 * Quote Request API Endpoint
 */
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
}

$name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
$phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
$email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$productName = isset($_POST['product_name']) ? sanitize($_POST['product_name']) : '';
$quantity = isset($_POST['quantity']) ? sanitize($_POST['quantity']) : '';
$message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

// Validation
if (empty($name) || empty($phone) || empty($productName)) {
    jsonResponse(['success' => false, 'message' => 'Please fill in all required fields.'], 400);
}

try {
    $db = getDB();

    // Save quote request
    $stmt = $db->prepare('INSERT INTO quote_requests (name, email, phone, product_name, quantity, message) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $email, $phone, $productName, $quantity, $message]);

    // Also create a lead
    $requirement = 'Quote Request: ' . $productName;
    if (!empty($quantity)) {
        $requirement .= ' - Qty: ' . $quantity;
    }
    $stmt2 = $db->prepare('INSERT INTO leads (customer_name, phone, email, requirement, lead_source) VALUES (?, ?, ?, ?, ?)');
    $stmt2->execute([$name, $phone, $email, $requirement, 'Quote Request']);

    jsonResponse(['success' => true, 'message' => 'Quote request submitted successfully! Our team will contact you soon with the best pricing.']);

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
}
