<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Details</title>
</head>
<body>
<?php if (isset($book)): ?>
    <h1><?= $book->getTitle(); ?></h1>
    <p>Author: <?= $book->getAuthor(); ?></p>
    <p>Language: <?= $book->getLanguage(); ?></p>
    <p>Year: <?= $book->getYear()->getValue(); ?></p>
    <form action="/books/<?= $book->getBookId(); ?>/borrow" method="POST">
        <button type="submit" <?= $book->isAvailable() ? '' : 'disabled'; ?>>Borrow</button>
    </form>
    <form action="/books/<?= $book->getBookId(); ?>/return" method="POST">
        <button type="submit" <?= !$book->isAvailable() ? '' : 'disabled'; ?>>Return</button>
    </form>
<?php else: ?>
    <p>Book not found</p>
<?php endif; ?>
</body>
</html>
