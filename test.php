<?php
require_once 'Autoloader.php';

$worker = new \EzHttp\Worker('0.0.0.0:8322');
$worker->count = 4;
$worker->run();