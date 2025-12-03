<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Interfaces/LoggableInterface.php';
require_once __DIR__ . '/../app/Exceptions/CustomException.php';
require_once __DIR__ . '/../app/Helpers/CSRF.php';
require_once __DIR__ . '/../app/Services/FileLogger.php';
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Book.php';
require_once __DIR__ . '/../app/Models/Log.php';
require_once __DIR__ . '/../app/Models/Review.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/BookController.php';
require_once __DIR__ . '/../app/Controllers/LogController.php';
require_once __DIR__ . '/../app/Controllers/ReviewController.php';

use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\LogController;
use App\Controllers\ReviewController;

session_start();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Simple router
if ($path === '/' || $path === '/index.php') {
    if (isset($_SESSION['user_id'])) {
        header("Location: /dashboard");
        exit;
    }
    $controller = new AuthController();
    $controller->showLoginForm();
} elseif ($path === '/login') {
    $controller = new AuthController();
    $controller->login();
} elseif ($path === '/logout') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($path === '/signup') {
    // Implement signup if needed, for now redirect to login or show signup form
    // Assuming AuthController handles it or we reuse login view with a toggle
    echo "Signup not implemented yet, please seed users or implement signup.";
} elseif ($path === '/dashboard') {
    $controller = new BookController();
    $controller->index();
} elseif (preg_match('#^/book/(\d+)$#', $path, $matches)) {
    $controller = new BookController();
    $controller->show($matches[1]);
} elseif ($path === '/log') {
    $controller = new LogController();
    $controller->updateStatus();
} elseif ($path === '/my-logs') {
    $controller = new LogController();
    $controller->index();
} elseif ($path === '/review') {
    $controller = new ReviewController();
    $controller->addReview();
} else {
    http_response_code(404);
    echo "404 Not Found";
}
