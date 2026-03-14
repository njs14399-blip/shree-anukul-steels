<?php
/**
 * Contact Form API Endpoint
 */
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
}

$name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? sanitize($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

// Validation
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    jsonResponse(['success' => false, 'message' => 'Please fill in all required fields.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(['success' => false, 'message' => 'Please enter a valid email address.'], 400);
}

// reCAPTCHA verification
$recaptchaSecret = getSetting('recaptcha_secret_key', '');
if (!empty($recaptchaSecret)) {
    $recaptchaResponse = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
    if (empty($recaptchaResponse)) {
        jsonResponse(['success' => false, 'message' => 'Please complete the reCAPTCHA verification.'], 400);
    }
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $verifyData = ['secret' => $recaptchaSecret, 'response' => $recaptchaResponse];
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($verifyData)
        ]
    ];
    $context = stream_context_create($options);
    $verifyResult = file_get_contents($verifyUrl, false, $context);
    $verifyResultData = json_decode($verifyResult, true);
    if (!$verifyResultData || !$verifyResultData['success']) {
        jsonResponse(['success' => false, 'message' => 'reCAPTCHA verification failed.'], 400);
    }
}

try {
    $db = getDB();

    // Save to enquiries table
    $stmt = $db->prepare('INSERT INTO enquiries (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $email, $phone, $subject, $message]);

    // Also create a lead
    $stmt2 = $db->prepare('INSERT INTO leads (customer_name, phone, email, city, requirement, lead_source) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt2->execute([$name, $phone, $email, '', $subject . ': ' . $message, 'Website Contact Form']);

    jsonResponse(['success' => true, 'message' => 'Thank you for contacting us! We will get back to you shortly.']);

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
}
