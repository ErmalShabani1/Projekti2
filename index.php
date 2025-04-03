<?php
require_once 'inicializuesi.php';

// Use the User class instead of session directly
$user = new User();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Focus Flow - Lajmet e fundit tek ne!">
    <title>Projekti</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header id="indexheader">
        <div class="Pjesa1">
            <h1 id="T1">Focus Flow</h1>
            <h2 id="T2">Lajmet e fundit vetem tek ne</h2>
        </div>
    </header>
    <section class="sec1">
        <nav>
            <div class="Links">
            <ul class="loginlist">
    <?php if ($user->isLoggedIn()): ?>
        <li><a href="logout.php" id="pjesalogin">Logout</a></li>
    <?php else: ?>
        <li><a href="login.php" id="pjesalogin">Login</a></li>
    <?php endif; ?>
</ul>
                <ul class="mainmenu">
                    <li><a href="index.php" id="Home">Home</a></li>
                    <li><a href="about.php" id="About">About</a></li>
                    <li><a href="contact.php" id="Contact">Kategorite</a></li>
                </ul>
            </div>
            <hr>
        </nav>
    </section>
    <section class="sec6">
        <article id="art3">
            <a href="https://www.bbc.com/news/topics/c77jz3mdmnxt" id="indexlink1">
                <img src="images/image1.jpg" id="image1">
                <h2 id="T16">Shembulli i pare i faqes kryesore lajmi per Nasa</h2>
            </a>
        </article>
        <article id="art4">
            <a href="https://www.bbc.com/news/topics/cwmm89gpwktv" id="indexlink1">
                <img src="images/image2.jpg" id="image2">
                <h2 id="T16">Shembulli i dyte i faqes kryesore lajmi per per Notre Dame</h2>
            </a>
        </article>
    </section>
    <hr>
    <p id="T16">© 2024 Our Company. All rights reserved.</p>
    <hr>
</body>
</html>