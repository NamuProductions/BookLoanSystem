<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Details</title>
</head>
<body>
<?php if (isset($book)) : ?>
    <h1><?= $book->title(); ?></h1>
    <p>Author: <?= $book->author(); ?></p>
    <p>Language: <?= $book->language(); ?></p>
    <p>Year: <?= $book->year()->value(); ?></p>
    <p>Pages: <?= $book->pages(); ?></p>
    <p>Genre: <?= $book->genre(); ?></p>
    <form action="/books/<?= $book->bookId(); ?>/borrow" method="POST">
        <input type="hidden" name="user_id" value="user_id_placeholder">
        <button type="submit" <?= $book->isAvailable() ? '' : 'disabled'; ?>>Borrow</button>
    </form>
    <form action="/books/<?= $book->bookId(); ?>/return" method="POST">
        <button type="submit" <?= !$book->isAvailable() ? '' : 'disabled'; ?>>Return</button>
    </form>
<?php else : ?>
    <p>Book not found</p>
<?php endif; ?>
</body>
</html>
