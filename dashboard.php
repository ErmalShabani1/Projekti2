<?php
require_once 'inicializuesi.php';

// Use the User class instead of session directly
$user = new User();

// Ensure the user is an admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header("Location: login.php");
    exit();
}

// Handle deletion of pages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_page'])) {
    $page_name_to_delete = $_POST['page_name'];
    
    // Use the static method from PageManager - properly OOP approach
    if (PageManager::deleteAllPageReferences($page_name_to_delete, $user)) {
        // Successfully deleted
        header("Location: dashboard.php?msg=page_deleted");
    } else {
        header("Location: dashboard.php?error=delete_failed");
    }
    exit();
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['user_id'];
    
    if ($user->deleteUser($user_id_to_delete)) {
        // Successfully deleted
        header("Location: dashboard.php?msg=user_deleted");
    } else {
        header("Location: dashboard.php?error=delete_failed");
    }
    exit();
}

// Handle role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    
    if ($user->changeUserRole($user_id, $new_role)) {
        // Successfully changed role
        header("Location: dashboard.php?msg=role_changed");
    } else {
        header("Location: dashboard.php?error=role_change_failed");
    }
    exit();
}

// Get page stats using PageManager
$pageStats = PageManager::getPageStats();
$saved_pages_stats = $pageStats['saved'];
$liked_pages_stats = $pageStats['liked'];

// Get all users
$users = $user->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <div class="nav-buttons">
            <button id="homeButton"><a href="index.php">Home</a></button>
            <button id="logoutButton"><a href="logout.php">Logout</a></button>
        </div>
    </header>

    <?php if (isset($_GET['msg'])): ?>
        <div class="message success">
            <?php if ($_GET['msg'] === 'user_deleted'): ?>
                User deleted successfully.
            <?php elseif ($_GET['msg'] === 'role_changed'): ?>
                User role updated successfully.
            <?php elseif ($_GET['msg'] === 'page_deleted'): ?>
                Page deleted successfully.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="message error">
            <?php if ($_GET['error'] === 'delete_failed'): ?>
                Operation failed. Please try again.
            <?php elseif ($_GET['error'] === 'role_change_failed'): ?>
                Failed to change role. You cannot change your own role.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <section class="stats">
        <h2>User Management</h2>
        <?php if (!empty($users)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $usr): ?>
                        <tr>
                            <td><?= htmlspecialchars($usr['id']) ?></td>
                            <td><?= htmlspecialchars($usr['username']) ?></td>
                            <td><?= htmlspecialchars($usr['email']) ?></td>
                            <td><?= htmlspecialchars($usr['role']) ?></td>
                            <td>
                                <?php if ($usr['id'] != $user->getUserId()): /* Don't allow actions on yourself */ ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($usr['id']) ?>">
                                        <select name="new_role">
                                            <option value="user" <?= $usr['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $usr['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                        <button type="submit" name="change_role">Change Role</button>
                                    </form>
                                    
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($usr['id']) ?>">
                                        <button type="submit" name="delete_user">Delete</button>
                                    </form>
                                <?php else: ?>
                                    <em>Current User</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </section>

    <section class="stats">
        <h2>Page Stats</h2>
        <?php if (!empty($saved_pages_stats)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Page Name</th>
                        <th>Users Saved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($saved_pages_stats as $page_stat): ?>
                        <tr>
                            <td><?= htmlspecialchars($page_stat['page_name']) ?></td>
                            <td><?= htmlspecialchars($page_stat['user_count']) ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this page from all user saved lists?');">
                                    <input type="hidden" name="page_name" value="<?= htmlspecialchars($page_stat['page_name']) ?>">
                                    <button type="submit" name="delete_page">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pages saved by users yet.</p>
        <?php endif; ?>
    </section>

    <section class="stats">
        <h2>Liked Pages Stats</h2>
        <?php if (!empty($liked_pages_stats)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Page Name</th>
                        <th>Users Liked</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liked_pages_stats as $page_stat): ?>
                        <tr>
                            <td><?= htmlspecialchars($page_stat['page_name']) ?></td>
                            <td><?= htmlspecialchars($page_stat['user_count']) ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this page from all user liked lists?');">
                                    <input type="hidden" name="page_name" value="<?= htmlspecialchars($page_stat['page_name']) ?>">
                                    <button type="submit" name="delete_page">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pages liked by users yet.</p>
        <?php endif; ?>
    </section>
</body>
</html>