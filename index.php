<?php
session_start();
require 'db.php';

// Check returning visitor
$visitorToken = $_COOKIE['visitor_token'] ?? null;
$user = null;
if ($visitorToken) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE visitor_token = ?");
    $stmt->execute([$visitorToken]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Event Photos</title>
    <!-- 1. Link to the external CSS file -->
    <link rel="stylesheet" href="style.css">
</head>
<!-- 2. Added the 'upload-theme' class to the body -->
<body class="upload-theme">

    <!-- 3. Changed 'container' to 'upload-container' to match the CSS -->
    <div class="upload-container">
        <h2>Live Event Slideshow</h2>
        <p class="subtitle">Share your photos to the big screen instantly</p>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-banner">✨ Photos successfully sent to the TV!</div>
        <?php endif; ?>

        <?php if ($user): ?>
            <div class="welcome-badge">
                👋 Welcome back, <?= htmlspecialchars($user['name']) ?>! <br> 
                Your previous photos are already in the loop.
            </div>
        <?php endif; ?>
        
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="visitor_name" placeholder="e.g. Alex Johnson" value="<?= $user ? htmlspecialchars($user['name']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Select Photos (Max 5MB)</label>
                <input type="file" name="photos[]" multiple accept="image/jpeg, image/png, image/gif" required>
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" name="consent" id="consent" value="1" required>
                <label for="consent">
                    <strong>Public Display Consent:</strong> I agree to have my name and uploaded photos displayed publicly on the venue slideshow.
                </label>
            </div>
            
            <button type="submit">Upload & Join Slideshow</button>
        </form>
    </div>

</body>
</html>