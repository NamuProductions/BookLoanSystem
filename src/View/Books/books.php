<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
</head>
<body>
<h1>Books</h1>
<ul>
    <?php if (!empty($books)) : ?>
        <?php foreach ($books as $book) : ?>
            <li>
                <a href="/books/<?= $book->bookId(); ?>"><?= $book->title(); ?></a>
                - <?= $book->isAvailable() ? 'Available' : 'Borrowed'; ?>
            </li>
        <?php endforeach; ?>
    <?php else : ?>
        <li>No books available</li>
    <?php endif; ?>
</ul>
</body>
</html>
