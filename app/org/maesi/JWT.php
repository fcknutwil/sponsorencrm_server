<?php
namespace org\maesi;


class JWT
{
    private static $config;

    public static function create() {
        return \Firebase\JWT\JWT::encode(self::getToken(), self::getPrivateKey(), self::getAlgorithm());
    }

    public static function config(array $config) {
        self::$config = $config;
    }

    public static function getPrivateKey() {
        return self::$config['privateKey'];

    }

    private static function getToken() {
        return self::$config['token'];
    }
    private static function getAlgorithm() {
        return self::$config['algorithm'];
    }

}
