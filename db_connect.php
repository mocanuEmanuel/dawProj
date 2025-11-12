<?php
$db_file = __DIR__ . "/booklog.db";

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS authors (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            bio TEXT,
            birth_year INTEGER
        );

        CREATE TABLE IF NOT EXISTS genres (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT UNIQUE NOT NULL
        );

        CREATE TABLE IF NOT EXISTS books (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            publication_year INTEGER,
            cover_image TEXT,
            language TEXT,
            openlibrary_work_id TEXT,
            author_id INTEGER NOT NULL,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS book_genres (
            book_id INTEGER NOT NULL,
            genre_id INTEGER NOT NULL,
            PRIMARY KEY (book_id, genre_id),
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
            FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            book_id INTEGER NOT NULL,
            status TEXT CHECK(status IN ('reading', 'completed', 'planned')),
            started_at DATETIME,
            finished_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            book_id INTEGER NOT NULL,
            rating INTEGER CHECK(rating BETWEEN 1 AND 5),
            review_text TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS favorites (
            user_id INTEGER NOT NULL,
            book_id INTEGER NOT NULL,
            PRIMARY KEY (user_id, book_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
        );
    ");

    // Schema created (no output to avoid sending data before headers when included)

} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>