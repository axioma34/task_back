<?php

final class Database
{
    CONST HOST = 'localhost:3306';
    CONST DB_NAME = 'test_db';
    CONST USERNAME = 'root';
    const PASSWORD = 'root';
    private static $conn;


    public static function connect()
    {

        if (!isset(self::$conn)) {
            try {
                self::$conn = new PDO(
                    'mysql:host=' . self::HOST .
                    ';dbname=' . self::DB_NAME,
                    self::USERNAME,
                    self::PASSWORD
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $ex) {
                echo 'Connection Error: ' . $ex->getMessage();
            }
        }

        return self::$conn;
    }
}
