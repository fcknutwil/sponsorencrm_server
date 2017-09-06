<?php
if($PROD_MODE) {
    require 'db.config.php';
} else {
    require 'dev.db.config.php';
}

require_once __DIR__ . '/vendor/autoload.php';

\org\maesi\DB::config($db_config);

$app = new \Slim\App();
new \ch\fcknutwil\Login($app);
// Run app
$app->run();
