<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['consent'])) die("Consent required.");
    
    $name = trim($_POST['visitor_name']);
    $normalizedName = strtolower(preg_replace('/\s+/', '', $name));
    
    $token = $_COOKIE['visitor_token'] ?? null;
    $userId = null;
    
    // Check if the cookie matches the person currently uploading
    if ($token) {
        $stmt = $pdo->prepare("SELECT id, normalized_name FROM users WHERE visitor_token = ?");
        $stmt->execute([$token]);
        $existingUser = $stmt->fetch();
        
        // If the name matches, treat them as a returning visitor
        if ($existingUser && $existingUser['normalized_name'] === $normalizedName) {
            $stmt = $pdo->prepare("UPDATE users SET visit_count = visit_count + 1 WHERE visitor_token = ?");
            $stmt->execute([$token]);
            $userId = $existingUser['id'];
        }
    }

    // If it's a new device OR a new name on the same device, create a new user
    if (!$userId) {
        $token = bin2hex(random_bytes(16));
        setcookie('visitor_token', $token, time() + (86400 * 30), "/");
        $stmt = $pdo->prepare("INSERT INTO users (name, normalized_name, visitor_token) VALUES (?, ?, ?)");
        $stmt->execute([$name, $normalizedName, $token]);
        $userId = $pdo->lastInsertId();
    }

    // Process Files
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['photos']['error'][$key] !== UPLOAD_ERR_OK) continue;
        
        $size = $_FILES['photos']['size'][$key];
        if ($size > 5000000) continue; // 5MB limit
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmpName);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) continue;
        
        $ext = explode('/', $mime)[1];
        $storedName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        
        if (move_uploaded_file($tmpName, $uploadDir . $storedName)) {
            $stmt = $pdo->prepare("INSERT INTO photos (user_id, stored_filename, original_filename, file_size, mime_type) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $storedName, $_FILES['photos']['name'][$key], $size, $mime]);
        }
    }
    header("Location: index.php?success=1");
}