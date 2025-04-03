<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit();
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['page'])) {
    echo json_encode(["success" => false, "message" => "Page not specified."]);
    exit();
}

$page = $data['page'];
$user_id = $_SESSION['user_id']; 

try {
    $pdo = new PDO("mysql:host=localhost;dbname=userdb", "root", "");

    // Check if the page is already "liked"
    $stmt = $pdo->prepare("SELECT * FROM liked_pages WHERE user_id = :user_id AND page_name = :page_name");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":page_name", $page);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Page already liked."]);
        exit();
    }

    // Add the page to the user's liked pages
    $stmt = $pdo->prepare("INSERT INTO liked_pages (user_id, page_name) VALUES (:user_id, :page_name)");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":page_name", $page);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Page liked successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to like the page."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
