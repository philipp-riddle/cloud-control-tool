<?php

require dirname(__FILE__).'/../vendor/autoload.php';

$app = new Phiil\CloudTools\Core\CloudApp();
$app->getCrawler()->crawl('/Users/philippmartini/Documents/Personal');
$app->getCrawler()->crawl('/Users/philippmartini/Documents/WhistlingMartini');