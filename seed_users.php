<?php
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Models/Model.php';
require_once __DIR__ . '/app/Models/User.php';

use App\Models\User;

$userModel = new User();
$username = "jerry";
$email = "jerry@example.com";
$password = "password";

$existing = $userModel->findByEmail($email);
if ($existing) {
    echo "User '$username' already exists.\n";
} else {
    if ($userModel->create($username, $email, $password)) {
        echo "User '$username' created successfully.\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
    } else {
        echo "Failed to create user.\n";
    }
}
