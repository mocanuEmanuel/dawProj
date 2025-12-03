<?php include 'header.php'; ?>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
<p style="color: #9ab;">Here are the popular books currently in the database.</p>

<div class="carousel-container">
    <button class="carousel-prev">&lt;</button>
    <div class="carousel-track-wrapper" style="overflow: hidden;">
        <div class="carousel-track" style="display: flex; gap: 20px; transition: transform 0.3s ease-in-out;">
            <?php foreach ($books as $book): ?>
                <a href="/book/<?= $book['id'] ?>" class="book-card" style="flex: 0 0 150px;">
                    <?php if ($book['cover_image']): ?>
                        <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-cover">
                    <?php else: ?>
                        <div class="book-cover" style="background: #333; display: flex; align-items: center; justify-content: center;">No Image</div>
                    <?php endif; ?>
                    <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                    <div class="book-author"><?= htmlspecialchars($book['author']) ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <button class="carousel-next">&gt;</button>
</div>

<style>
    .carousel-container {
        position: relative;
        display: flex;
        align-items: center;
    }
    .carousel-prev, .carousel-next {
        background: rgba(0,0,0,0.5);
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        z-index: 10;
        padding: 10px;
    }
    .carousel-prev:hover, .carousel-next:hover {
        background: rgba(0,0,0,0.8);
    }
</style>

<?php include 'footer.php'; ?>
