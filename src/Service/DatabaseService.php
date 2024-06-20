<?php
declare(strict_types=1);

namespace App\Service;

use PDO;

class DatabaseService
{
    private string $host = 'localhost';
    private string $port = '3307';
    private string $dbname;
    private string $userName = 'root';
    private string $password = 'root';
    private PDO $pdo;

    public function __construct()
    {
        $this->dbname = getenv('DB_NAME') ?: 'library';
        $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->dbname";
        $this->pdo = new PDO($dsn, $this->userName, $this->password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getDatabaseConnection(): PDO
    {
        return $this->pdo;
    }
}
