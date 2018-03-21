<?php
require_once 'Autoloader.php';
define('ROOT', __DIR__);

$worker = new \EzHttp\Worker('0.0.0.0:8322');
$worker->appRoot = ROOT;
$worker->count = 4;
$worker->run();