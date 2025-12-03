<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/Book.php';

use App\Models\Book;

echo "Running BookTest...\n";

$bookModel = new Book();

$bookModel = new Book();

echo "Testing get all books... ";
$books = $bookModel->getAllWithAuthors();
if (is_array($books)) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

echo "Testing get book by ID... ";
if (!empty($books)) {
    $firstBookId = $books[0]['id'];
    $book = $bookModel->getById($firstBookId);
    if ($book && $book['id'] == $firstBookId) {
        echo "PASS\n";
    } else {
        echo "FAIL\n";
    }
} else {
    echo "SKIP (No books found)\n";
}

echo "Testing delete method existence... ";
if (method_exists($bookModel, 'delete')) {
    echo "PASS\n";
} else {
    echo "FAIL\n";
}

echo "BookTest Complete.\n";
