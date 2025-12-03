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
            SELECT books.id, books.title, authors.name AS author, books.publication_year, books.cover_image
            FROM books
            JOIN authors ON books.author_id = authors.id
            ORDER BY books.title ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
