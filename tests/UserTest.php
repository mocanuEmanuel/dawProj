<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/User.php';

use App\Models\User;

echo "Running UserTest...\n";

$userModel = new User();

$username = "testuser_" . time();
$email = "test_" . time() . "@example.com";
$password = "password123";

echo "Testing create user... ";
if ($userModel->create($username, $email, $password)) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

echo "Testing find user by email... ";
$user = $userModel->findByEmail($email);
if ($user && $user['username'] === $username) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

echo "Testing password verification... ";
if ($userModel->verifyPassword($password, $user['password_hash'])) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

echo "UserTest Complete.\n";
