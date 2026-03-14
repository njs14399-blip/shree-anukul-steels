<?php
/**
 * Database Setup Script
 * Run this once to initialize the database
 */

$dbPath = __DIR__ . '/steels.db';
$schemaPath = __DIR__ . '/schema.sql';

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA journal_mode=WAL');
    $db->exec('PRAGMA foreign_keys=ON');

    // Read and execute schema
    $schema = file_get_contents($schemaPath);
    $db->exec($schema);

    // Insert default admin user with hashed password
    $hashedPassword = password_hash('12345678', PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT OR IGNORE INTO admin_users (username, password, name, email) VALUES (?, ?, ?, ?)');
    $stmt->execute(['anukulsteels', $hashedPassword, 'Admin', 'contact@shreeanukulsteels.com']);

    echo "Database setup completed successfully!\n";
    echo "Database file: " . $dbPath . "\n";
    echo "Admin username: anukulsteels\n";
    echo "Admin password: 12345678\n";

} catch (PDOException $e) {
    die('Database setup failed: ' . $e->getMessage() . "\n");
}
