<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books</title>
</head>
<body>
<h1>Books</h1>
<ul>
    <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
            <li>
                <a href="/books/<?= $book->getBookId(); ?>"><?= $book->getTitle(); ?></a>
                - <?= $book->isAvailable() ? 'Available' : 'Borrowed'; ?>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No books available</li>
    <?php endif; ?>
</ul>
</body>
</html>
