<?php
namespace org\maesi;

class DB {
    public static $TYP_SPONSOREN_CRM = 'sponsoren';
    public static $TYP_MITGLIEDER_CRM = 'mitglieder';
    public static $TYP_DONATOREN_CRM = 'donatoren';

    private static $connections = array();
    private static $configs = array();

    public static function instance($typ = 'sponsoren') {
        if(self::$connections[$typ] == null) {
            self::$connections[$typ] = new \Simplon\Mysql\Mysql(self::createPDO($typ));
        }
        return self::$connections[$typ];
    }

    private static function createPDO($typ) {
        if(self::$configs[$typ] == null) {
            throw new \Exception('MySQL-Konfiguration für den Typ ' + $typ + 'fehlt');
        }
        $dsn = 'mysql:host=' . self::$configs[$typ]['host'] . ';dbname=' . self::$configs[$typ]['database'];
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        return new \PDO($dsn, self::$configs[$typ]['user'], self::$configs[$typ]['password'], $options);
    }

    public static function config(array $config_sponsoren, array $config_mitglieder, array $config_donatoren) {
        self::$configs[DB::$TYP_SPONSOREN_CRM] = $config_sponsoren;
        self::$configs[DB::$TYP_MITGLIEDER_CRM] = $config_mitglieder;
        self::$configs[DB::$TYP_DONATOREN_CRM] = $config_donatoren;
    }
}