<?php include __DIR__ . '/header.php'; ?>

<div class="book-details-container">
    <div class="book-poster">
        <?php if ($book['cover_image']): ?>
            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Cover" class="book-cover">
        <?php else: ?>
            <div class="book-cover" style="background: #333; display: flex; align-items: center; justify-content: center;">No Image</div>
        <?php endif; ?>
    </div>

    <div class="book-info">
        <h1><?= htmlspecialchars($book['title']) ?> <span class="publication-year">(<?= $book['publication_year'] ?>)</span></h1>
        <h3>by <?= htmlspecialchars($book['author']) ?></h3>
        
        <p><?= htmlspecialchars($book['description']) ?></p>

        <div class="actions-panel">
            <h4>Log this book</h4>
            <form action="/log" method="POST">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <label>Status: 
                    <select name="status" class="form-control">
                        <option value="planned" <?= ($userLog['status'] ?? '') == 'planned' ? 'selected' : '' ?>>Planned</option>
                        <option value="reading" <?= ($userLog['status'] ?? '') == 'reading' ? 'selected' : '' ?>>Reading</option>
                        <option value="completed" <?= ($userLog['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </label>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

        <div class="actions-panel">
            <h4>Write a Review</h4>
            <form action="/review" method="POST">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <label>Rating: 
                    <input type="number" name="rating" min="1" max="5" value="5" class="rating-input">
                </label>
                <br><br>
                <textarea name="review_text" rows="4" class="form-control" placeholder="Write your review..."></textarea>
                <br><br>
                <button type="submit" class="btn btn-primary">Post Review</button>
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
                            <span class="review-date"><?= $review['created_at'] ?></span>
                        </p>
                        <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .book-details-container { display: flex; gap: 40px; margin-top: 20px; }
    .book-poster { flex: 0 0 300px; }
    .book-cover { width: 100%; border-radius: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
    .book-info { flex: 1; }
    .publication-year { font-weight: normal; color: var(--color-text-muted); }
    .actions-panel { background: var(--color-surface); padding: 20px; margin-bottom: 20px; border: 1px solid var(--color-border); }
    .actions-panel h4 { margin-top: 0; }
    .rating-input { width: 60px; padding: 5px; }
    .reviews-section { margin-top: 40px; }
    .review-card { background: var(--color-surface); padding: 15px; margin-bottom: 15px; border-bottom: 1px solid var(--color-border); }
    .rating-stars { color: #FFD700; margin-left: 10px; }
    .review-date { color: var(--color-text-muted); font-size: 0.8em; margin-left: 10px; }
    
    @media (max-width: 768px) {
        .book-details-container { flex-direction: column; }
        .book-poster { flex: 0 0 auto; width: 100%; max-width: 300px; margin: 0 auto; }
    }
</style>

<?php include __DIR__ . '/footer.php'; ?>
