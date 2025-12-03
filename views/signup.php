<?php include __DIR__ . '/header.php'; ?>

<div class="login-container">
    <h2>Sign Up</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="/signup" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \App\Helpers\CSRF::generate() ?>">
        
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="/login">Login</a></p>
</div>

<?php include __DIR__ . '/footer.php'; ?>
