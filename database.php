<?php
declare(strict_types=1);

$host = 'localhost';
$port = '3307';
$dbname = 'library';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Successfully connection!";
} catch (PDOException) {
    die("Connection error");
}
