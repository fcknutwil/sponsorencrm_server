<?php
require_once __DIR__ . '/vendor/autoload.php';

if($PROD_MODE) {
    require 'db.config.php';
} else {
    require 'dev.db.config.php';
}
\org\maesi\DB::config($db_config);

require 'jwt.config.php';
\org\maesi\JWT::config($jwt_config);



$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" => ["/test"],
    "secret" => \org\maesi\JWT::getPrivateKey()
]));
new \ch\fcknutwil\Login($app);
// Run app
$app->run();
