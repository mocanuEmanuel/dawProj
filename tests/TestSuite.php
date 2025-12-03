<?php
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Models/Model.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Book.php';
require_once __DIR__ . '/../app/Models/Log.php';
require_once __DIR__ . '/../app/Models/Review.php';

use App\Models\User;
use App\Models\Book;
use App\Models\Log;
use App\Models\Review;

function assertTest($condition, $message) {
    if ($condition) {
        echo "✅ PASS: $message\n";
    } else {
        echo "❌ FAIL: $message\n";
    }
}

echo "Running Unit Tests...\n\n";

// Setup
$userModel = new User();
$bookModel = new Book();
$logModel = new Log();
$reviewModel = new Review();
$timestamp = time();

// Test 1: Create User
$username = "testuser_$timestamp";
$email = "test_$timestamp@example.com";
$password = "password123";
$userModel->create($username, $email, $password);
$user = $userModel->findByEmail($email);
assertTest($user && $user['username'] === $username, "User creation");

// Test 2: Verify Password
assertTest($userModel->verifyPassword($password, $user['password_hash']), "Password verification");

// Test 3: Create Book (Simulated via direct insert for testing if create method isn't fully exposed or just use existing)
// We'll use the existing seed logic or just check if books exist
$books = $bookModel->getAllWithAuthors();
assertTest(count($books) > 0, "Book retrieval (Database seeded)");
$bookId = 1; // Assuming ID 1 exists from seed

// Test 4: Log a Book
$status = 'reading';
$logModel->createOrUpdate($user['id'], $bookId, $status);
$log = $logModel->getByUserAndBook($user['id'], $bookId);
assertTest($log && $log['status'] === $status, "Log creation/update");

// Test 5: Review a Book
$rating = 5;
$text = "Great book!";
$reviewModel->createOrUpdate($user['id'], $bookId, $rating, $text);
$reviews = $reviewModel->getByBook($bookId);
$foundReview = false;
foreach ($reviews as $r) {
    if ($r['user_id'] == $user['id'] && $r['review_text'] === $text) {
        $foundReview = true;
        break;
    }
}
assertTest($foundReview, "Review creation");

// Test 6: Update Log Status
$newStatus = 'completed';
$logModel->createOrUpdate($user['id'], $bookId, $newStatus);
$log = $logModel->getByUserAndBook($user['id'], $bookId);
assertTest($log && $log['status'] === $newStatus, "Log status update");

echo "\nTests Complete.\n";
