<?php include __DIR__ . '/header.php'; ?>

<h2>My Logs</h2>

<table>
    <thead>
        <tr>
            <th>Book</th>
            <th>Author</th>
            <th>Status</th>
            <th>Date Logged</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($logs)): ?>
            <tr><td colspan="4">No logs found.</td></tr>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><a href="/book/<?= $log['book_id'] ?>"><?= htmlspecialchars($log['title']) ?></a></td>
                    <td><?= htmlspecialchars($log['author']) ?></td>
                    <td>
                    <td>
                        <form action="/log" method="POST" class="status-form">
                            <input type="hidden" name="book_id" value="<?= $log['book_id'] ?>">
                            <input type="hidden" name="redirect_to" value="/my-logs">
                            <select name="status" onchange="this.form.submit()" class="status-select status-<?= $log['status'] ?>">
                                <option value="planned" <?= $log['status'] == 'planned' ? 'selected' : '' ?>>Planned</option>
                                <option value="reading" <?= $log['status'] == 'reading' ? 'selected' : '' ?>>Reading</option>
                                <option value="completed" <?= $log['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                    <td>
                        <form action="/log/delete" method="POST" onsubmit="return confirm('Are you sure you want to remove this log?');">
                            <input type="hidden" name="book_id" value="<?= $log['book_id'] ?>">
                            <input type="hidden" name="redirect_to" value="/my-logs">
                            <button type="submit" class="btn-delete-log">&times;</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<style>
    .status-form { display: inline-block; }
    .status-select {
        padding: 4px 8px;
        border-radius: 0; /* Sharp corners */
        color: #121212;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        appearance: none; /* Remove default arrow to look more like a badge */
        text-align: center;
    }
    .status-completed { background-color: #5D7A60; color: white; } /* Primary Green */
    .status-reading { background-color: #5D7A80; color: white; } /* Teal-ish */
    .status-planned { background-color: #7A7A7A; color: white; } /* Grey */
    
    .btn-delete-log {
        background: none;
        border: 1px solid var(--color-danger);
        color: var(--color-danger);
        width: 25px;
        height: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        padding: 0;
        transition: all 0.1s ease;
    }
    .btn-delete-log:hover {
        background: var(--color-danger);
        color: white;
    }
</style>

<?php include __DIR__ . '/footer.php'; ?>
