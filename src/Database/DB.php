<?php

namespace App\Database;

use App\Database\Exception\DbException;

class DB
{
    /**
     * @var \PDO
     */
    private static $pdo;

    public static function getConneciton()
    {
        $host = ($_ENV['IS_DEVMODE'] == "0")    ?   $_ENV['DB_HOST']        :   $_ENV['DEV_DB_HOST'];
        $port = ($_ENV['IS_DEVMODE'] == "0")    ?   $_ENV['DB_PORT']        :   $_ENV['DEV_DB_PORT'];
        $user = ($_ENV['IS_DEVMODE'] == "0")    ?   $_ENV['DB_USER']        :   $_ENV['DEV_DB_USER'];
        $pass = ($_ENV['IS_DEVMODE'] == "0")    ?   $_ENV['DB_PASSWORD']    :   $_ENV['DEV_DB_PASSWORD'];
        $dbname = ($_ENV['IS_DEVMODE'] == "0")  ?   $_ENV['DB_NAME']        :   $_ENV['DEV_DB_NAME'];

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];

        $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";

        try {
            self::$pdo = new \PDO($dsn, $user, $pass, $options);

            return self::$pdo;
        } catch (\PDOException $e) {
            throw new DbException("Error trying to establish a connection");
        }
    }

    public static function cloneConnection()
    {
        self::$pdo = null;
    }
}
