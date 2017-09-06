<?php
require_once __DIR__ . '/vendor/autoload.php';

require 'db.config.php';
\org\maesi\DB::config($db_config);

$app = new \Slim\App();
new \ch\fcknutwil\Login($app);
// Run app
$app->run();

?>
