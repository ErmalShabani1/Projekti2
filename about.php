<?php

$host = 'localhost';
$dbname = 'userdb';
$dbuser = 'root';
$dbpass = '';
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM users");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <header id="head1">
		<button id="AboutUsButton"><a href="index.php">Home</a></button>
        <h1 id="T9">Rreth Focus Flow !</h1>
    </header>
    <main>
        <section id="sec3">
            <h2 id="T10">FocusFlow</h2>
            <p id="T11">Portali FocusFlow filloi te krijohet ne vitin 2024 dhe u kompletua ne Janar te 2025 .</p>
        </section>
        <section id="sec4">
            <h2 id="T12">Krijuesit</h2>
            <p id="T13">Krijuesit e faqes jane Mire Kabashi dhe Ermal Shabani.</p>
        </section>
        <section id="sec5">
            <h2 id="T14">Portali</h2>
            <p id="T15">Portali bazohet vetem ne lajme qe jan 100% te sigurta.</p>
        </section>
    </main>
    <footer>
        <p id="T16">© 2024 Our Company. All rights reserved.</p>
    </footer>
</body>
</html>
