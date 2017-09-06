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
file_put_contents ( $destDir.'index.php', "<?php include 'crm.phar';");

// .htaccess
copy('app/.htaccess', $destDir . '.htaccess');
