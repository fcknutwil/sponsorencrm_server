<?php
require '../vendor/autoload.php';

if($PROD_MODE) {
    require '../db.config.php';
} else {
    require '../dev.db.config.php';
}
\org\maesi\DB::config($db_config, $db_config_crm, $db_config_donatoren_crm);
echo "DONE";
