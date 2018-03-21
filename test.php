<?php
require_once 'Autoloader.php';

$worker = new \EzHttp\Worker();
$worker->count = 4;
$worker->run();