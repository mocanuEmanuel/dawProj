<?php
namespace App\Models;

use PDO;

class Book extends Model {
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT books.*, authors.name AS author 
            FROM books 
            JOIN authors ON books.author_id = authors.id 
            WHERE books.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllWithAuthors() {
        $stmt = $this->pdo->query("
            SELECT books.title, authors.name AS author, books.publication_year
            FROM books
            JOIN authors ON books.author_id = authors.id
            ORDER BY books.title ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add other methods as needed for seeding or other functionality
    public function create($title, $description, $year, $cover, $language, $work_id, $author_id) {
        // Implementation for seeding if needed, or keep seeding separate
        // For now, focusing on read operations for the dashboard
    }
}
