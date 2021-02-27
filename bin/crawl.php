<?php

require dirname(__FILE__).'/../vendor/autoload.php';

$app = new Phiil\CloudTools\Core\CloudApp();

for ($i = 1; $i < \count($argv); $i++) {
    $app->getCrawler()->crawl($argv[$i]);
}