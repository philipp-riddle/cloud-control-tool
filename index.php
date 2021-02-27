<?php

require 'bin/bootstrap.php';

$app = new \Phiil\CloudTools\Core\CloudApp();
$controllerName = \htmlentities($_GET['controller'] ?? 'index');

$resolver = new \Phiil\CloudTools\Controller\ControllerResolver($app);
$resolver->load();

echo $resolver->resolve($controllerName);