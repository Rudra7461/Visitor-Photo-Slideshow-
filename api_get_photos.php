<?php
require 'db.php';
header('Content-Type: application/json');

// Get all photos with consent, join with users to get names and returning status
$stmt = $pdo->query("
    SELECT p.stored_filename, u.name, u.visit_count 
    FROM photos p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.consent_given = 1 
    ORDER BY p.id DESC
");
$photos = $stmt->fetchAll();

echo json_encode(['photos' => $photos]);