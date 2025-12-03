<?php
namespace App\Controllers;

use App\Models\Book;

class BookController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }

        $bookModel = new Book();
        $books = $bookModel->getAllWithAuthors();

        require_once __DIR__ . '/../../views/dashboard.php';
    }

    public function show($id) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $bookModel = new Book();
        $book = $bookModel->getById($id);

        if (!$book) {
            echo "Book not found";
            return;
        }

        $logModel = new \App\Models\Log();
        $userLog = $logModel->getByUserAndBook($_SESSION['user_id'], $id);

        $reviewModel = new \App\Models\Review();
        $reviews = $reviewModel->getByBook($id);

        require_once __DIR__ . '/../../views/book_details.php';
    }
}
