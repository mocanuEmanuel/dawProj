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
                session_write_close();
                
                $logger = new FileLogger();
                $logger->log("User logged in: " . $user['username']);

                header("Location: /dashboard");
                exit;
            } else {
                $error = "Invalid email or password.";
                require __DIR__ . '/../../views/login.php';
                return;
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

    public function showSignupForm() {
        require_once __DIR__ . '/../../views/signup.php';
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            CSRF::verify($_POST['csrf_token'] ?? '');

            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $password = $_POST["password"];

            if (empty($username) || empty($email) || empty($password)) {
                $error = "All fields are required.";
                require __DIR__ . '/../../views/signup.php';
                return;
            }

            $userModel = new User();
            if ($userModel->findByEmail($email)) {
                $error = "Email already exists.";
                require __DIR__ . '/../../views/signup.php';
                return;
            }

            if ($userModel->create($username, $email, $password)) {
                // Auto login after signup
                $user = $userModel->findByEmail($email);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                session_write_close();

                $logger = new FileLogger();
                $logger->log("New user registered and logged in: " . $username);

                header("Location: /dashboard");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
                require __DIR__ . '/../../views/signup.php';
                return;
            }
        }
        // If GET, just show form
        $this->showSignupForm();
    }
}
