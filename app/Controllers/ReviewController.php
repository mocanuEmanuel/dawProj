<?php
namespace App\Controllers;

use App\Models\Review;

class ReviewController {
    public function addReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header("Location: /login");
                exit;
            }

            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'];
            $rating = $_POST['rating'];
            $text = $_POST['review_text'];

            $reviewModel = new Review();
            $reviewModel->createOrUpdate($userId, $bookId, $rating, $text);

            header("Location: /book/" . $bookId);
            exit;
        }
    }
}
