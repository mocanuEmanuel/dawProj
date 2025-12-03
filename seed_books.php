<?php
// seed_books.php
// Seeds booklog.db with enriched book, author, and genre data from Open Library.

require_once __DIR__ . '/app/Config/Database.php';

use App\Config\Database;

/**
 * Fetch book metadata from Open Library API by title.
 */
function getBookMetadata($title)
{
    $url = "https://openlibrary.org/search.json?title=" . urlencode($title) . "&limit=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($ch);
    curl_close($ch);

    if (!$json) {
        echo "  → Failed to fetch data for '$title'\n";
        return null;
    }

    $data = json_decode($json, true);
    return $data['docs'][0] ?? null;
}

/**
 * Fetch popular book titles dynamically.
 */
function getPopularBooks($limit = 50)
{
    $url = "https://openlibrary.org/subjects/popular.json?limit=" . $limit;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($ch);
    curl_close($ch);

    if (!$json) {
        echo "⚠️ Could not load popular books from Open Library.\n";
        return [];
    }

    $data = json_decode($json, true);
    $titles = [];
    foreach ($data['works'] as $work) {
        $titles[] = $work['title'];
    }

    echo "📚 Loaded " . count($titles) . " popular book titles from Open Library.\n";
    return $titles;
}

/**
 * Insert or retrieve an author ID.
 */
function insertAuthor($pdo, $name, $bio = null, $birth_year = null)
{
    $stmt = $pdo->prepare("SELECT id FROM authors WHERE name = :name");
    $stmt->execute([':name' => $name]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['id'];
    }

    $stmt = $pdo->prepare("INSERT INTO authors (name, bio, birth_year) VALUES (:name, :bio, :by)");
    $stmt->execute([':name' => $name, ':bio' => $bio, ':by' => $birth_year]);
    return $pdo->lastInsertId();
}

/**
 * Insert or retrieve a genre ID.
 */
function insertGenre($pdo, $genreName)
{
    $stmt = $pdo->prepare("SELECT id FROM genres WHERE name = :name");
    $stmt->execute([':name' => $genreName]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['id'];
    }

    $stmt = $pdo->prepare("INSERT INTO genres (name) VALUES (:name)");
    $stmt->execute([':name' => $genreName]);
    return $pdo->lastInsertId();
}

/**
 * Insert a book record.
 */
function insertBook($pdo, $title, $description, $year, $cover, $language, $work_id, $author_id)
{
    $stmt = $pdo->prepare("SELECT id FROM books WHERE title = :title AND author_id = :author");
    $stmt->execute([':title' => $title, ':author' => $author_id]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        return null; // already exists
    }

    $stmt = $pdo->prepare("
        INSERT INTO books
        (title, description, publication_year, cover_image, language, openlibrary_work_id, author_id)
        VALUES (:t, :desc, :year, :cover, :lang, :workid, :author)
    ");

    $stmt->execute([
        ':t' => $title,
        ':desc' => $description,
        ':year' => $year,
        ':cover' => $cover,
        ':lang' => $language,
        ':workid' => $work_id,
        ':author' => $author_id
    ]);

    return $pdo->lastInsertId();
}

/**
 * Link a book to a genre.
 */
function linkBookGenre($pdo, $book_id, $genre_id)
{
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO book_genres (book_id, genre_id) VALUES (:b, :g)");
    $stmt->execute([':b' => $book_id, ':g' => $genre_id]);
}

/**
 * Fetch full work details to get subjects/genres.
 */
function getWorkDetails($workKey)
{
    $url = "https://openlibrary.org" . $workKey . ".json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $json = curl_exec($ch);
    curl_close($ch);

    if (!$json)
        return null;
    return json_decode($json, true);
}

/**
 * Fetch author details for bio and birth date, with fallback to search.
 */
function getAuthorFullDetails($authorKey, $authorName)
{
    $bio = null;
    $birthYear = null;

    if ($authorKey) {
        $authorDetails = getWorkDetails("/authors/" . $authorKey); // reuse work details function

        // Bio
        $bio = $authorDetails['bio'] ?? 'No bio yet';
        if (is_array($bio)) {
            $bio = $bio['value'] ?? 'No bio yet';
        }

        // Birth date
        $birthDate = $authorDetails['birth_date'] ?? null;

        // fallback using search endpoint if missing
        if (!$birthDate) {
            $searchUrl = "https://openlibrary.org/search/authors.json?q=" . urlencode($authorName);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $searchUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $searchJson = curl_exec($ch);
            curl_close($ch);

            $searchData = json_decode($searchJson, true);
            $birthDate = $searchData['docs'][0]['birth_date'] ?? null;
        }

        if ($birthDate) {
            $birthYear = (int) preg_replace('/\D/', '', $birthDate);
        }
    }

    return [$bio, $birthYear];
}

// --------------------------------------------------
// Main Script
// --------------------------------------------------
$pdo = Database::getInstance()->getConnection();

echo "<pre>";

$bookList = getPopularBooks(50);
if (empty($bookList)) {
    $bookList = [
        "To Kill a Mockingbird",
        "1984",
        "Pride and Prejudice",
        "The Hobbit",
        "The Catcher in the Rye",
        "The Great Gatsby",
        "Fahrenheit 451",
        "Brave New World",
        "Moby Dick",
        "Jane Eyre"
    ];
    echo "⚠️ Using fallback list of classic books.\n";
}

foreach ($bookList as $title) {
    echo "\nProcessing book: $title\n";
    $meta = getBookMetadata($title);
    if (!$meta) {
        echo "  → Metadata not found.\n";
        continue;
    }

    // Author
    $authorName = $meta['author_name'][0] ?? 'Unknown Author';
    $authorKey = $meta['author_key'][0] ?? null;
    [$authorBio, $birthYear] = getAuthorFullDetails($authorKey, $authorName);
    $author_id = insertAuthor($pdo, $authorName, $authorBio, $birthYear);

    // Book metadata
    $description = $meta['first_sentence'] ?? '';
    $pubYear = $meta['first_publish_year'] ?? null;
    $cover = isset($meta['cover_i']) ? "https://covers.openlibrary.org/b/id/{$meta['cover_i']}-L.jpg" : null;
    $language = $meta['language'][0] ?? null;
    $workId = $meta['key'] ?? null;

    $book_id = insertBook($pdo, $meta['title'], $description, $pubYear, $cover, $language, $workId, $author_id);
    if (!$book_id) {
        echo "  → Book already exists.\n";
        continue;
    }

    // Genres/subjects
    if ($workId) {
        $workDetails = getWorkDetails($workId);
        $subjects = $workDetails['subjects'] ?? [];
        $count = 0;
        foreach ($subjects as $subj) {
            if ($count >= 5)
                break;
            $genre_id = insertGenre($pdo, $subj);
            linkBookGenre($pdo, $book_id, $genre_id);
            $count++;
        }
    }

    echo "  → Inserted book ID = $book_id (author: $authorName)\n";
}

echo "\n✅ Seeding complete.\n";
echo "</pre>";