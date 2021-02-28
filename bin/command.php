<?php

require dirname(__FILE__).'/../vendor/autoload.php';

$app = new Phiil\CloudTools\Core\ConsoleApp();

$commandName = $argv[1] ?? null;

if (null === $commandName) { // print out available commands to help the user
    echo 'available commands: '.PHP_EOL;

    foreach ($app->getResolver()->getRegisteredCommands() as $commandName => $command) {
        echo '  > '.$commandName.PHP_EOL;
    }

    return 0;
} 

$args = \array_splice($argv, 2);
$app->getResolver()->resolve($commandName, $args)->execute();
