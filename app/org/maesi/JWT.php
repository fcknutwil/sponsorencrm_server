<?php
namespace org\maesi;


class JWT
{
    private static $config;

    public static function create(array $payload = array()) {
        return \Firebase\JWT\JWT::encode(array_merge(self::getToken(), $payload), self::getPrivateKey(), self::getAlgorithm());
    }

    public static function getClaim($token, $claim) {
        $decoded = \Firebase\JWT\JWT::decode($token, self::getPrivateKey(), [self::getAlgorithm()]);
        $array = get_object_vars($decoded);
        return $array[$claim];
    }

    public static function config(array $config) {
        self::$config = $config;
    }

    public static function getPrivateKey() {
        return self::$config['privateKey'];

    }

    private static function getToken() {
        $current = time();
        $arr = array();
        $arr['iat'] = $current;
        $arr['nbf'] = $current;
        $arr['exp'] = $current + (60*60);
        return array_merge(self::$config['token'], $arr);
    }
    private static function getAlgorithm() {
        return self::$config['algorithm'];
    }

}
