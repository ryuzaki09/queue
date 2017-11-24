<?php
namespace System\configs;

use \PDO;
use \System\Ini;

class DB 
{

    protected static $instance;
    private $pdo;

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            return new self();
        }

        return self::$instance;
    }

    private function connect()
    {
        $username   = Ini::getConfig("database.mysql_username");
        $pwd        = Ini::getConfig("database.mysql_password");
        $host       = Ini::getConfig("database.host");
        $dbName     = Ini::getConfig("database.dbname");

        try {
            $this->pdo = new PDO("mysql:host=$host; dbname=$dbName", $username, $pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // print_r($pdo);

        } catch (PDOException $e) {
            echo "cannot connect: ".$e->getMessage();
        }

    }

    public function getConnection()
    {
        return $this->pdo;
    }

}
