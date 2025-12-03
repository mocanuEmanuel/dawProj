<?php include 'header.php'; ?>

<div class="book-details-container">
    <div class="book-poster">
        <?php if ($book['cover_image']): ?>
            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover" class="book-cover">
        <?php else: ?>
            <div class="book-cover" style="background: #333; display: flex; align-items: center; justify-content: center;">No Image</div>
        <?php endif; ?>
    </div>

    <div class="book-info">
        <h1><?= htmlspecialchars($book['title']) ?> <span style="font-weight: normal; color: #89a;">(<?= $book['publication_year'] ?>)</span></h1>
        <h3>by <?= htmlspecialchars($book['author']) ?></h3>
        
        <p><?= htmlspecialchars($book['description']) ?></p>

        <div class="actions-panel">
            <h4>Log this book</h4>
            <form action="/log" method="POST">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <label>Status: 
                    <select name="status">
                        <option value="planned" <?= ($userLog['status'] ?? '') == 'planned' ? 'selected' : '' ?>>Planned</option>
                        <option value="reading" <?= ($userLog['status'] ?? '') == 'reading' ? 'selected' : '' ?>>Reading</option>
                        <option value="completed" <?= ($userLog['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </label>
                <button type="submit">Update</button>
            </form>
        </div>

        <div class="actions-panel">
            <h4>Write a Review</h4>
            <form action="/review" method="POST">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <label>Rating: 
                    <input type="number" name="rating" min="1" max="5" value="5" style="width: 50px;">
                </label>
                <br><br>
                <textarea name="review_text" rows="4" style="width: 100%;" placeholder="Write your review..."></textarea>
                <br><br>
                <button type="submit">Post Review</button>
            </form>
        </div>

        <div class="reviews-section">
            <h3>Reviews</h3>
            <?php if (empty($reviews)): ?>
                <p>No reviews yet.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <p>
                            <strong><?= htmlspecialchars($review['username']) ?></strong> 
                            <span class="rating-stars"><?= str_repeat('★', $review['rating']) ?></span>
                            <span style="color: #678; font-size: 0.8em;"><?= $review['created_at'] ?></span>
                        </p>
                        <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
