<?php
namespace org\maesi;

class DB {

    public static $connection;
    private static $config;

    public static function instance() {
        if(self::$connection == null) {
            if(self::$config == null) {
                throw new \Exception('MySQL-Konfiguration fehlt');
            }
            self::$connection = new \Simplon\Mysql\Mysql(
                self::$config['host'],
                self::$config['user'],
                self::$config['password'],
                self::$config['database']
            );
        }
        return self::$connection;
    }

    public static function config(array $config) {
        self::$config = $config;
    }
}