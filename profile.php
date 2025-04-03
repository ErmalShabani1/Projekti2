<?php
require_once 'inicializuesi.php';

// Use the User class instead of session directly
$user = new User();

// Check if user is logged in
if (!$user->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get user information
$user_id = $user->getUserId();
$saved_pages = [];
$liked_pages = [];

// Create PageManager for current user
$pageManager = new PageManager($user_id);

// Fetch saved and liked pages
$saved_pages = $pageManager->getSavedPages();
$liked_pages = $pageManager->getLikedPages();

// Handle removal of saved pages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_saved_page'])) {
    $page_name_to_remove = $_POST['page_name'];
    
    if ($pageManager->removeSavedPage($page_name_to_remove)) {
        // Refresh the page to reflect changes
        header("Location: profile.php");
        exit();
    }
}

// Handle removal of liked pages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_liked_page'])) {
    $page_name_to_remove = $_POST['page_name'];
    
    if ($pageManager->removeLikedPage($page_name_to_remove)) {
        // Refresh the page to reflect changes
        header("Location: profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="profile.css">
</head>
<body>
    <h1>Welcome to Your Profile</h1>
    <button id="contactButton"><a href="index.php">Home</a></button>

    <?php if (!empty($saved_pages)): ?>
        <h2>Your Saved Pages</h2>
        <div class="slider">
            <?php foreach ($saved_pages as $page): ?>
                <div class="slide">
                    <a href="<?= htmlspecialchars($page['page_name']) ?>">
                        <?= htmlspecialchars($page['page_name']) ?>
                    </a>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="page_name" value="<?= htmlspecialchars($page['page_name']) ?>">
                        <button type="submit" name="remove_saved_page" id="removeButton">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No pages saved yet.</p>
    <?php endif; ?>

    <?php if (!empty($liked_pages)): ?>
        <h2>Your Liked Pages</h2>
        <div class="slider">
            <?php foreach ($liked_pages as $page): ?>
                <div class="slide">
                    <a href="<?= htmlspecialchars($page['page_name']) ?>">
                        <?= htmlspecialchars($page['page_name']) ?>
                    </a>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="page_name" value="<?= htmlspecialchars($page['page_name']) ?>">
                        <button type="submit" name="remove_liked_page" id="removeButton">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No pages liked yet.</p>
    <?php endif; ?>
</body>
</html>