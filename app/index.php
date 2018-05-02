<?php
require_once __DIR__ . '/vendor/autoload.php';

if($PROD_MODE) {
    require 'db.config.php';
} else {
    require 'dev.db.config.php';
}
\org\maesi\DB::config($db_config, $db_config_crm, $db_config_donatoren_crm);

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
    "path" => [\ch\fcknutwil\api\Base::getPath()],
    "secret" => \org\maesi\JWT::getPrivateKey(),
    "header" => "X-Authorization",
    "error" => function ($request, $response, $arguments) {
        $data = [];
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->withJson($data);
    }
]));
new \ch\fcknutwil\Login($app);
new \ch\fcknutwil\Download($app);
new \ch\fcknutwil\api\Sponsor($app);
new \ch\fcknutwil\api\Engagement($app);
new \ch\fcknutwil\api\Typ($app);
new \ch\fcknutwil\api\Ort($app);
new \ch\fcknutwil\api\Beziehungen($app);
// Run app
$app->run();
