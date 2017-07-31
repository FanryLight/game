<?php
class DBConnector {
    const HOST = 'localhost';
    const DBNAME = 'game';
    const USER = 'root';
    const PASS = '';

    private $pdo;
    private static $instance;

    /**
     * @return DBConnector
     */
    public static function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=".DBConnector::HOST.";dbname=".DBConnector::DBNAME,
                DBConnector::USER,
                DBConnector::PASS
            );
        } catch (PDOException $e) {
            die(''.$e->getMessage());
        }
    }

    /**
     * @param string $query
     * @return PDOStatement
     */
    public function query($query)
    {
        return $this->pdo->query($query);
    }

    /**
     * @param string $query
     * @return PDOStatement
     */
    public  function prepare($query) {
        return $this->pdo->prepare($query);
    }

    /**
     * @return int
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}