<?php

namespace Phiil\CloudTools\Core\Command;

use Phiil\CloudTools\Core\Structures\ParameterBag;

class CommandArgumentResolution
{
    protected $command;
    protected $args;

    protected $success = false;
    protected $flagsBag;
    protected $argumentsBag;

    public function __construct(Command $command, array $args)
    {
        $this->command = $command;
        $this->_resolveArguments($args);
    }

    public function execute()
    {
        return $this->success = $this->command->execute($this);
    }

    protected function _resolveArguments(array $rawArgs)
    {
        $this->flagsBag = new ParameterBag();
        $this->argumentsBag = new ParameterBag();

        foreach ($rawArgs as $arg) {
            if ('--' === \substr($arg, 0, 2)) { // is flag
                $this->flagsBag->add(\substr($arg, 2));
            } else {
                $this->argumentsBag->add($arg);
            }
        }
    }

    public function getCommand(): Command
    {
        return $this->command;
    }

    public function getFlags(): ParameterBag
    {
        return $this->flagsBag;
    }

    public function getArguments(): ParameterBag
    {
        return $this->argumentsBag;
    }

    public function wasSuccessful(): bool
    {
        return $this->success;
    }
}
