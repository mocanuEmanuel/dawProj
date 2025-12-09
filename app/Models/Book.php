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

    public function create($title, $authorName, $description, $year) {
        try {
            $this->pdo->beginTransaction();

            // Find or create author
            $stmt = $this->pdo->prepare("SELECT id FROM authors WHERE name = :name");
            $stmt->execute([':name' => $authorName]);
            $author = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($author) {
                $authorId = $author['id'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO authors (name) VALUES (:name)");
                $stmt->execute([':name' => $authorName]);
                $authorId = $this->pdo->lastInsertId();
            }

            // Insert book
            $stmt = $this->pdo->prepare("INSERT INTO books (title, author_id, description, publication_year) VALUES (:title, :author_id, :description, :year)");
            $stmt->execute([
                ':title' => $title,
                ':author_id' => $authorId,
                ':description' => $description,
                ':year' => $year
            ]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
