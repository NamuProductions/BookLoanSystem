<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<nav>
    <ul>
        <?php if (isset($_SESSION['userId'])): ?>
            <li><a href="/books">Books</a></li>
            <li><a href="/loans">Loans</a></li>
            <li><a href="/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="/register">Register</a></li>
            <li><a href="/login">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
</body>
</html>
