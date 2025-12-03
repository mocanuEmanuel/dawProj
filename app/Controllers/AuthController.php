<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\CSRF;
use App\Services\FileLogger;

class AuthController {
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            CSRF::verify($_POST['csrf_token'] ?? '');

            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && $userModel->verifyPassword($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                $logger = new FileLogger();
                $logger->log("User logged in: " . $user['username']);

                header("Location: /dashboard");
                exit;
            } else {
                return "Invalid email or password.";
            }
        }
        require_once __DIR__ . '/../../views/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /");
        exit;
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../../views/login.php';
    }
}
