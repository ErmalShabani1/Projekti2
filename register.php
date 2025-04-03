<?php
include_once('connect.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['emri'], $_POST['email'], $_POST['password'])) {
        $emri = $conn->real_escape_string($_POST['emri']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $email_check_query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($email_check_query);
        if (!$stmt) {
            echo "Email Check Prepare failed: (" . $conn->errno . ") " . $conn->error;
            exit;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already registered!";
            exit;
        }

        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $emri, $email, $password);

            if ($stmt->execute()) {
                echo "User registered successfully."; 
            } else {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        }
    } else {
        echo "Invalid input data.";
    }

    $conn->close();
    exit; 
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="register.js"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header id="regheader">
        <h1 id="T4">Mirë se erdhët!</h1>
        <h2 id="T5">Ju lutem Log In</h2>
        <button id="HomeButton"><a href="index.php">Home</a></button>
    </header>
    <div class="pjesareg">
        <form action="register.php" method="POST" id="regform">
            <label for="emri">Emri:</label>
            <input type="text" name="emri" id="emri" required><br>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>
            
            <label for="password">Fjalkalimi:</label>
            <input type="password" name="password" id="password" required><br>
            
            <label for="confirm_password">Komfirmo Fjalkalimin:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>
            
            <button type="submit" name="registerBtn" id="regbutton">Register</button>
            <a href="login.php" type="button" id="regbutton">Ke llogari? Kyqu</a>
        </form>
    </div>
    <hr>
    <div id="art2">
        <a href="https://www.facebook.com" target="_blank"><img src="images/fblogo.png" alt="img1" id="fblogo"></a>
        <a href="https://www.instagram.com" target="_blank"><img src="images/iglogo.jfif" alt="img2" id="iglogo"></a>
        <a href="https://www.linkedin.com" target="_blank"><img src="images/lilogo.png" alt="img3" id="lilogo"></a>
    </div>
    <h3 id="T8">Faleminderit</h3>
    <hr>
</body>
</html>
