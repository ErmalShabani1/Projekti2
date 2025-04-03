<?php
// filepath: c:\laragon\www\Projekti\login.php
require_once 'inicializuesi.php';

// Use the User class instead of session directly
$user = new User();

// Already logged in? Redirect to index
if ($user->isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Ju lutem plotësoni të gjitha fushat.';
    } else {
        // Use the User class login method
        if ($user->login($username, $password)) {
            // Successful login
            header("Location: index.php");
            exit();
        } else {
            $error = 'Emri i përdoruesit ose fjalëkalimi nuk është i saktë.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header id="loginheader">
        <h1 id="T4">Mirë se erdhët!</h1>
        <h2 id="T5">Ju lutem Log In</h2>
        <button id="HomeButton"><a href="index.php">Home</a></button>
    </header>
    <div class="pjesalogin2">
        <?php if ($user->isLoggedIn()): ?>
            <p>Welcome, <?php echo htmlspecialchars($user->getUsername()); ?>!</p>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <form action="login.php" method="POST" id="logform">
                <input type="text" name="username" placeholder="Emri" required id="logintext">
                <input type="password" name="password" placeholder="Fjalkalimi" required id="logintext">
                <input type="submit" name="login" value="Kyqu" id="loginbutton">
                <a href="register.php" type="button" id="SignUp">S'ke llogari? Krijo</a>
            </form> 
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
    <hr>
    <div id="art2">
        <a href="https://www.facebook.com" target="_blank"> <img src="images/fblogo.png" alt="img1" id="fblogo"></a>
        <a href="https://www.instagram.com" target="_blank"> <img src="images/iglogo.jfif" alt="img2" id="iglogo"></a>
        <a href="https://www.linkedin.com" target="_blank"> <img src="images/lilogo.png" alt="img3" id="lilogo"></a>
    </div>
    <h3 id="T8">Faleminderit</h3>
    <hr>
</body>
</html>