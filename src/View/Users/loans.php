<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Loans</title>
</head>
<body>
<h1>Your Loans</h1>
<?php if (empty($loans)): ?>
    <p>You have no loans.</p>
<?php else: ?>
    <ul>
        <?php foreach ($loans as $loan): ?>
            <li><?php echo htmlspecialchars($loan['book_title']); ?> (Due: <?php echo htmlspecialchars($loan['due_date']); ?>)</li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
