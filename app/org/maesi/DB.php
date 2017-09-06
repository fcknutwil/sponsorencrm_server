<?php
namespace org\maesi;

class DB {

    public static $connection;
    private static $config;

    public static function instance() {
        if(self::$connection == null) {
            self::$connection = new \Simplon\Mysql\Mysql(self::createPDO());
        }
        return self::$connection;
    }

    private static function createPDO() {
        if(self::$config == null) {
            throw new \Exception('MySQL-Konfiguration fehlt');
        }
        $dsn = 'mysql:host=' . self::$config['host'] . ';dbname=' . self::$config['database'];
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        return new \PDO($dsn, self::$config['user'], self::$config['password'], $options);
    }

    public static function config(array $config) {
        self::$config = $config;
    }
}