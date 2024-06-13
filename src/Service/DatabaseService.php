<?php
declare(strict_types=1);

namespace App\Service;

use PDO;
use PDOException;

class DatabaseService
{
    private PDO $databaseConnection;

    public function __construct()
    {
        $host = 'localhost';
        $port = '3307';
        $dbname = 'library';
        $username = 'root';
        $password = 'root';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";

        try {
            $this->databaseConnection = new PDO($dsn, $username, $password);
            $this->databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Successfully connection";
        } catch (PDOException) {
            die("Connection error");
        }
    }

    public function getDatabaseConnection(): PDO
    {
        return $this->databaseConnection;
    }
}
