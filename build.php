<?php

$destDir = 'dist/';

if (!is_dir($destDir)) {
    mkdir($destDir);
}

$pharName = 'crm.phar';

// Create Phar
$phar = new Phar($destDir.$pharName, 0, $pharName);
$phar->buildFromDirectory(dirname(__FILE__) . '/app', '/\.php$/');
$phar->setStub($phar->createDefaultStub());

// Create Index.php
file_put_contents ( $destDir.'index.php', '<?php $PROD_MODE=true; include \'crm.phar\';');
file_put_contents ( $destDir.'job.php', '<?php $PROD_MODE=true; $script = empty($argv[1]) ? \'expire\' : $argv[1]; include \'phar:///\'.__DIR__.\'/crm.phar/jobs/\'.$script.\'.php\';');

// .htaccess
copy('app/.htaccess', $destDir . '.htaccess');
file_put_contents ( $destDir.'.htaccess', "Action php /cgi-php71/php\nAddHandler php71 .php\n" . file_get_contents ( $destDir.'.htaccess'));
