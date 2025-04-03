<?php
require_once 'inicializuesi.php';

// Use the User class instead of session directly
$user = new User();

// Check if user is logged in with the isLoggedIn method
if (!$user->isLoggedIn()) {
    header("Location: login.php");  
    exit();
}

// Get user information using the User class methods
$id = $user->getUserId();
$username = $user->getUsername();

// Use Database class for query
$db = Database::getInstance();
$conn = $db->getConnection();

$query = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $email = $userData['email']; 
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Page</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body> 
    <header>
        <h1>FlowFocus</h1>
        <p>Lajmet e fundit vetem tek ne !</p>
    </header>

    <section class="kategorit">
        <article id="art5">
            <h2>Eksploroj Kategorit</h2>
                <button id="contactButton"><a href="index.php">Home</a></button>
                <br>
                <button id="contactButton"><a href="about.php">About Us</a></button>
                <br>
                <button id="contactButton"><a href="logout.php">Logout</a></button>
                <br>
                <button id="contactButton"><a href="profile.php">Profile</a></button>
                <br>
                <?php if ($user->isAdmin()): ?>
                    <button id="contactButton"><a href="dashboard.php">Admin Dashboard</a></button>
                <?php endif; ?>
        </article>
        <article id="art6">
        <div id="lajmi1"><a href="faqja1.php" class="faqet"><img src="images/image3.jpg" alt="Image1" id="fotolajmi1"><h6 id="titujt">Granit Xhaka nenshkruan me Bayer Leverkusen</h6></a></div>
        <div id="lajmi2"><a href="faqja2.php" class="faqet"><img src="images/image4.jpg" alt="Image2" id="fotolajmi2"><h6 id="titujt">TikTok-ut i ndalohetperdorimi ne USA</h6></a></div>
        <div id="lajmi3"><a href="faqja3.php" class="faqet"><img src="images/image5.jpg" alt="Image3" id="fotolajmi3"><h6 id="titujt">Donald Trump lanson kriptomonedhen e tij $Trump</h6></a></div>
        <div id="lajmi4"><a href="faqja4.php" class="faqet"><img src="images/image6.jpg" alt="Image4" id="fotolajmi4"><h6 id="titujt">Lojtari i Manchester United kalon ne Real Betis</h6></a></div>
        </article>
    </section>

    <footer class="footer">
        <h4>Rreth nesh !</h4>
        <p>Na ndiq ne keto platforma</p>
        <div class="logo">
            <a href="https://www.facebook.com" target="_blank" class="logot"><img src="images/fblogo.png" alt="img1" id="fblogo"></a>
            <a href="https://www.instagram.com" target="_blank" class="logot"><img src="images/iglogo.jfif" alt="img1" id="iglogo"></a>
            <a href="https://www.linkedin.com" target="_blank" class="logot"><img src="images/lilogo.png" alt="img1" id="lilogo"></a>
        </div>
    </footer>
</body>
</html>