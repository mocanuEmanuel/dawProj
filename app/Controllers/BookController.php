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
            // Handle error (redirect back with error?)
            header("Location: /book/create");
            exit;
        }

        $pdo = \App\Config\Database::getInstance()->getConnection();
        
        // Find or create author
        $stmt = $pdo->prepare("SELECT id FROM authors WHERE name = :name");
        $stmt->execute([':name' => $authorName]);
        $author = $stmt->fetch();
        
        if ($author) {
            $authorId = $author['id'];
        } else {
            $stmt = $pdo->prepare("INSERT INTO authors (name) VALUES (:name)");
            $stmt->execute([':name' => $authorName]);
            $authorId = $pdo->lastInsertId();
        }

        $bookModel = new Book();
        
        $stmt = $pdo->prepare("INSERT INTO books (title, author_id, description, publication_year) VALUES (:title, :author_id, :description, :year)");
        $stmt->execute([
            ':title' => $title,
            ':author_id' => $authorId,
            ':description' => $description,
            ':year' => $year
        ]);

        header("Location: /dashboard");
        exit;
    }

    public function destroy($id) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        // In a real app, check if user is admin or owner. 
        // For this demo, allow any logged in user to delete.
        
        $bookModel = new Book();
        $bookModel->delete($id); // Assuming Model has delete

        header("Location: /dashboard");
        exit;
    }
}
