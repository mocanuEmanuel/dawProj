<?php
require_once "db_connect.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

$stmt = $pdo->query("
    SELECT books.title, authors.name AS author, books.publication_year
    FROM books
    JOIN authors ON books.author_id = authors.id
    ORDER BY books.title ASC
");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Book Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin-top: 30px;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <h2> Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p>Here are the books in the database:</p>

    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Publication Year</th>
        </tr>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['publication_year']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="logout.php">Log out</a></p>
</body>

</html>