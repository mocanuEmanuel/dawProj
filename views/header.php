<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookLog</title>
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body>
    <header>
        <h1>BookLog</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <nav>
                <a href="/dashboard">Dashboard</a>
                <a href="/my-logs">My Logs</a>
                <a href="/logout">Logout</a>
            </nav>
        <?php endif; ?>
    </header>
    <main>
