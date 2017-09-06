<?php
require_once __DIR__ . '/vendor/autoload.php';

require 'db.config.php';
\org\maesi\DB::config($config);

$app = new \Slim\App();

// Run app
$app->run();

$con = mysqli_connect("db","sponsoren_crm_user","sponsoren_crm_password","sponsoren_crm");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . exit();
} else {
    echo "Connection erstellt";
}
?>
