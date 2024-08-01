<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<?php if (!empty($errors)) : ?>
    <div style="color: red;">
        <?php foreach ($errors as $error) : ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="/login" method="POST">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($oldValues['username'] ?? '') ?>">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
    </div>
    <button type="submit">Login</button>
</form>
</body>
</html>
