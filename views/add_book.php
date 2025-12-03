<?php include __DIR__ . '/header.php'; ?>

<div class="container add-book-container">
    <h2>Add New Book</h2>
    <form action="/book/store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \App\Helpers\CSRF::generate() ?>">
        
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required class="form-control">
        </div>

        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required class="form-control">
        </div>

        <div class="form-group">
            <label for="publication_year">Publication Year:</label>
            <input type="number" id="publication_year" name="publication_year" class="form-control">
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="5" class="form-control"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Book</button>
            <a href="/dashboard" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<style>
    .add-book-container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-control { width: 100%; padding: 10px; margin-bottom: 10px; }
    .form-actions { display: flex; align-items: center; gap: 10px; margin-top: 20px; }
    .btn-cancel { text-decoration: none; color: var(--color-text-muted); }
    .btn-cancel:hover { color: var(--color-text-main); }
</style>

<?php include __DIR__ . '/footer.php'; ?>
