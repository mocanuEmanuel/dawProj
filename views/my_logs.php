<?php include 'header.php'; ?>

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
                        <span style="
                            padding: 4px 8px; 
                            border-radius: 4px; 
                            background: <?= $log['status'] === 'completed' ? '#00e054' : ($log['status'] === 'reading' ? '#40bcf4' : '#ff8000') ?>;
                            color: #14181c;
                            font-weight: bold;
                            text-transform: uppercase;
                            font-size: 0.8rem;
                        ">
                            <?= htmlspecialchars($log['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
