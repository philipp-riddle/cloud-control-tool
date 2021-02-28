<?php

namespace Phiil\CloudTools\Command;

use Phiil\CloudTools\Core\Command\Command;
use Phiil\CloudTools\Core\Command\CommandArgumentResolution;
use Phiil\CloudTools\Database\Entity\CommandRun;
use Phiil\CloudTools\Database\Repository\CommandRunRepository;

abstract class CloudCommand extends Command
{
    public function postExecute(CommandArgumentResolution $resolution)
    {
        $commandRun = new CommandRun();

        $commandRun
            ->setCommand($this->getName())
            ->setUser('CLI')
            ->setMeta($this->meta->getAll())
            ->setStartedAt($this->startedAt)
            ->setEndedAt($this->endedAt)
            ->setSuccess($resolution->wasSuccessful());

        $this->_getCommandRunRepository()->flush($commandRun);
    }

    protected function _getCommandRunRepository(): CommandRunRepository
    {
        return new CommandRunRepository($this->_getMongoService());
    }
}
