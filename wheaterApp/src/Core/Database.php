<?php

namespace App\Core;
//require_once 'Config.php';

class Database
{
    public static $connection;

    public function connect()
    {
        if (! self::$connection) {
            $dsn = "mysql:host=" . 'localhost' . ";dbname=". 'khordir_wheaterApp' . ";charset=UTF8";
            try {
                self::$connection = new \PDO($dsn, 'khordir_parham', '1381pm1381pm');
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    public function connectionStatus()
    {
        return self::$connection;
    }
}
