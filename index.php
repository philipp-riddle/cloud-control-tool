<?php

require 'bin/bootstrap.php';

$app = new \Phiil\CloudTools\Core\CloudWebApp();
$controllerName = \htmlentities($_GET['controller'] ?? 'index');

echo $app->getResolver()->resolve($controllerName);