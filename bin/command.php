<?php

require dirname(__FILE__).'/../vendor/autoload.php';

$app = new Phiil\CloudTools\Core\ConsoleApp();

$commandName = $argv[1] ?? null;

if (null === $commandName) { // print out available commands to help the user
    echo 'available commands: '.PHP_EOL;

    foreach ($app->getResolver()->getRegisteredCommands() as $commandName => $command) {
        echo '  > '.$commandName.PHP_EOL;
    }

    return 1; // quits the script with exit code 1
} 

$args = \array_splice($argv, 2);

try {
    $app->getResolver()->resolve($commandName, $args)->execute();
} catch (\Phiil\CloudTools\Core\Exception\CommandException $ex) {
    echo $ex->getMessage().PHP_EOL;

    return 1;
}

return 0;