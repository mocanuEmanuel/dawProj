<?php
namespace App\Models;

use PDO;

class Log extends Model {
    public function createOrUpdate($userId, $bookId, $status) {
        // Check if exists
        $stmt = $this->pdo->prepare("SELECT id FROM logs WHERE user_id = :uid AND book_id = :bid");
        $stmt->execute([':uid' => $userId, ':bid' => $bookId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE logs SET status = :status, created_at = CURRENT_TIMESTAMP WHERE id = :id");
            return $stmt->execute([':status' => $status, ':id' => $existing['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO logs (user_id, book_id, status) VALUES (:uid, :bid, :status)");
            return $stmt->execute([':uid' => $userId, ':bid' => $bookId, ':status' => $status]);
        }
    }

    public function getByUserAndBook($userId, $bookId) {
        $stmt = $this->pdo->prepare("SELECT * FROM logs WHERE user_id = :uid AND book_id = :bid");
        $stmt->execute([':uid' => $userId, ':bid' => $bookId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT logs.*, books.title, authors.name AS author
            FROM logs
            JOIN books ON logs.book_id = books.id
            JOIN authors ON books.author_id = authors.id
            WHERE logs.user_id = :uid
            ORDER BY logs.created_at DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
