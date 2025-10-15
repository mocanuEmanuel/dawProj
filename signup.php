<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:u, :e, :p)");
        $stmt->execute([
            ':u' => $username,
            ':e' => $email,
            ':p' => $password_hash
        ]);
        echo "<p>Signup successful!</p>";
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'UNIQUE')) {
            echo "<p style='color:red;'>Email already registered.</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>