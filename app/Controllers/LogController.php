<?php
namespace App\Controllers;

use App\Models\Log;

class LogController {
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /login");
                exit;
            }

            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'];
            $status = $_POST['status'];
            $redirectTo = $_POST['redirect_to'] ?? "/book/" . $bookId;

            $logModel = new Log();
            $logModel->createOrUpdate($userId, $bookId, $status);

            header("Location: " . $redirectTo);
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /login");
                exit;
            }

            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'];
            $redirectTo = $_POST['redirect_to'] ?? "/my-logs";

            $logModel = new Log();
            $logModel->delete($userId, $bookId);

            header("Location: " . $redirectTo);
            exit;
        }
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $logModel = new Log();
        $logs = $logModel->getAllByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../../views/my_logs.php';
    }
}
