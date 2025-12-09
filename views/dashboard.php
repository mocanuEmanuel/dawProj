<?php include __DIR__ . '/header.php'; ?>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
<p style="color: #9ab;">Here are the books currently in the database.</p>

<div class="dashboard-actions">
    <a href="/book/create" class="btn btn-primary">Add New Book</a>
</div>

<div class="carousel-container">
    <button class="carousel-prev">&lt;</button>
    <div class="carousel-track-wrapper">
        <div class="carousel-track">
            <?php foreach ($books as $book): ?>
                <div class="book-card-wrapper">
                    <a href="/book/<?= $book['id'] ?>" class="book-card">
                        <?php if ($book['cover_image']): ?>
                            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-cover">
                        <?php else: ?>
                            <div class="book-cover-placeholder">No Image</div>
                        <?php endif; ?>
                        <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                        <div class="book-author"><?= htmlspecialchars($book['author']) ?></div>
                    </a>
                    <form action="/book/delete/<?= $book['id'] ?>" method="POST" onsubmit="return confirm('Are you sure?');" class="delete-form">
                        <button type="submit" class="btn-delete">&times;</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <button class="carousel-next">&gt;</button>
</div>

<style>
    .dashboard-actions { margin-bottom: 20px; }
    .carousel-container { position: relative; display: flex; align-items: center; }
    .carousel-track-wrapper { overflow: hidden; width: 100%; }
    .carousel-track { display: flex; gap: 20px; transition: transform 0.3s ease-in-out; }
    .book-card-wrapper { position: relative; flex: 0 0 150px; }
    .book-card { display: block; text-decoration: none; color: inherit; }
    .book-cover { width: 150px; height: 225px; object-fit: cover; }
    .book-cover-placeholder { width: 150px; height: 225px; background: #333; display: flex; align-items: center; justify-content: center; color: #aaa; }
    .book-title { margin-top: 10px; font-weight: bold; }
    .book-author { color: var(--color-text-muted); }
    .delete-form { position: absolute; top: 0; right: 0; }
    .btn-delete { background: var(--color-danger); color: white; border: none; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; padding: 0; }
    .carousel-prev, .carousel-next { background: rgba(0,0,0,0.5); border: none; color: white; font-size: 2rem; cursor: pointer; z-index: 10; padding: 10px; height: 100%; position: absolute; }
    .carousel-prev { left: 0; }
    .carousel-next { right: 0; }
    .carousel-prev:hover, .carousel-next:hover { background: rgba(0,0,0,0.8); }
</style>

<?php include __DIR__ . '/footer.php'; ?>
