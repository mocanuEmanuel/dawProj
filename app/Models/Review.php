<?php
namespace App\Models;

use PDO;

class Review extends Model {
    public function createOrUpdate($userId, $bookId, $rating, $text) {
        // Check if exists
        $stmt = $this->pdo->prepare("SELECT id FROM reviews WHERE user_id = :uid AND book_id = :bid");
        $stmt->execute([':uid' => $userId, ':bid' => $bookId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE reviews SET rating = :rating, review_text = :text, created_at = CURRENT_TIMESTAMP WHERE id = :id");
            return $stmt->execute([':rating' => $rating, ':text' => $text, ':id' => $existing['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, book_id, rating, review_text) VALUES (:uid, :bid, :rating, :text)");
            return $stmt->execute([':uid' => $userId, ':bid' => $bookId, ':rating' => $rating, ':text' => $text]);
        }
    }

    public function getByBook($bookId) {
        $stmt = $this->pdo->prepare("
            SELECT reviews.*, users.username 
            FROM reviews 
            JOIN users ON reviews.user_id = users.id 
            WHERE book_id = :bid 
            ORDER BY created_at DESC
        ");
        $stmt->execute([':bid' => $bookId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
