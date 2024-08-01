<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<?php if (!empty($errors)) : ?>
    <div style="color: red;">
        <?php foreach ($errors as $error) : ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="/register" method="POST">
    <div>
        <label for="user_name">Username:</label>
        <input type="text" id="user_name" name="user_name" value="<?= htmlspecialchars($oldValues['user_name'] ?? '') ?>">
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($oldValues['email'] ?? '') ?>">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($oldValues['full_name'] ?? '') ?>">
    </div>
    <div>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" value="<?= htmlspecialchars($oldValues['age'] ?? '') ?>">
    </div>
    <button type="submit">Register</button>
</form>
</body>
</html>
