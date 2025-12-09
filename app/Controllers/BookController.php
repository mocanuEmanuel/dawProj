<?php
namespace App\Controllers;

use App\Models\Book;
use App\Exceptions\ValidationException;
use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\LoggableInterface;
use App\Services\FileLogger;

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
            throw new ResourceNotFoundException("Book with ID $id not found");
        }

        $logModel = new \App\Models\Log();
        $userLog = $logModel->getByUserAndBook($_SESSION['user_id'], $id);

        $reviewModel = new \App\Models\Review();
        $reviews = $reviewModel->getByBook($id);

        require_once __DIR__ . '/../../views/book_details.php';
    }
    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        require_once __DIR__ . '/../../views/add_book.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /");
            exit;
        }

        \App\Helpers\CSRF::verify($_POST['csrf_token'] ?? '');

        $title = $_POST['title'];
        $authorName = $_POST['author'];
        $description = $_POST['description'];
        $year = $_POST['publication_year'];
        // Basic validation
        if (empty($title) || empty($authorName)) {
            throw new ValidationException(['title' => 'Title is required', 'author' => 'Author is required']);
        }

        $bookModel = new Book();
        $bookModel->create($title, $authorName, $description, $year);

        // Log the action
        $logger = new FileLogger();
        $logger->log("Book created: '$title' by user ID " . $_SESSION['user_id']);

        header("Location: /dashboard");
        exit;
    }

    public function destroy($id) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }
        
        $bookModel = new Book();
        $bookModel->delete($id); 

        header("Location: /dashboard");
        exit;
    }
}
