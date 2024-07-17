<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
<form action="/register" method="POST">
    <label for="user_name">User Name:</label>
    <input type="text" id="user_name" name="user_name" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name"><br><br>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age"><br><br>
    <button type="submit">Register</button>
</form>
</body>
</html>
