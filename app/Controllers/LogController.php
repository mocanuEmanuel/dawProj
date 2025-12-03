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

            $logModel = new Log();
            $logModel->createOrUpdate($userId, $bookId, $status);

            header("Location: /book/" . $bookId);
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
