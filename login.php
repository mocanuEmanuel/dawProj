<?php
require_once "db_connect.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :e");
    $stmt->execute([':e' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<p style='color:red; text-align:center;'>Invalid email or password.</p>";
        echo "<p style='text-align:center;'><a href='index.html'>Try again</a></p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>