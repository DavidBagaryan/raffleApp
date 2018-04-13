<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.04.2018
 * Time: 17:32
 */

namespace app\models;


use PDO;

class DataBase
{
    /**
     * @var PDO
     */
    protected static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return PDO
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) return self::$instance = self::getConnection();
        else return self::$instance;
    }

    /**
     * @return PDO
     */
    private static function getConnection()
    {
        $host = HOST;
        $dbName = DB_NAME;
        $user = USER;
        $pass = PASSWORD;
        $charset = CHARSET;

        $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        return new PDO($dsn, $user, $pass, $opt);
    }
}