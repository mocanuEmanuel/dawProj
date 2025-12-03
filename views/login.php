<?php include 'header.php'; ?>

<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \App\Helpers\CSRF::generate() ?>">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="/signup">Sign up</a></p>
</div>

<?php include 'footer.php'; ?>
