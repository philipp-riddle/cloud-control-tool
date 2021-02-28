<?php

namespace Phiil\CloudTools\Core\Command;

use Phiil\CloudTools\Command\DeleteTypeCommand;
use Phiil\CloudTools\Core\ConsoleApp;
use Phiil\CloudTools\Core\Exception\CommandNotFoundException;

class CommandResolver
{
    protected $app;
    protected $commands;

    public function __construct(ConsoleApp $app)
    {
        $this->app = $app;
        $this->commands = [];
    }

    public function load()
    {
        $this->registerCommand(new DeleteTypeCommand($this));
    }

    public function registerCommand(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function resolve(string $commandName, array $args): CommandArgumentResolution
    {
        if (!isset($this->commands[$commandName])) {
            throw new CommandNotFoundException('Could not find command: '.$commandName);
        }

        return new CommandArgumentResolution($this->commands[$commandName], $args);
    }

    /**
     * @return Command[] all registered commands
     */
    public function getRegisteredCommands(): array
    {
        return $this->commands;
    }

    public function getApp(): ConsoleApp
    {
        return $this->app;
    }
}