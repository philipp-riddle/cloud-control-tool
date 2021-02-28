<?php

namespace Phiil\CloudTools\Core\Command;

use DateTime;
use Phiil\CloudTools\Core\Database\MongoService;
use Phiil\CloudTools\Core\Structures\ParameterBag;

abstract class Command
{
    protected $name;
    protected $resolver;

    protected $style;
    protected $success;
    protected $startedAt, $endedAt;
    protected $meta;

    public function __construct(string $name, CommandResolver $resolver)
    {
        $this->name = $name;
        $this->resolver = $resolver;

        $this->style = new CommandStyle();
        $this->meta = new ParameterBag();
    }

    public function execute(CommandArgumentResolution $resolution): bool
    {
        $this->startedAt = new DateTime();
        $success = $this->_execute($resolution);
        $this->endedAt = new DateTime();

        $this->postExecute($resolution);

        return $success;
    }

    /**
     * This function gets called right after the execution of the command got executed
     */
    public function postExecute(CommandArgumentResolution $resolution)
    {
        // can be overriden - doing nothing on default
    }

    abstract protected function _execute(CommandArgumentResolution $resolution): bool;

    public function getName(): string
    {
        return $this->name;
    }

    public function getResolver(): CommandResolver
    {
        return $this->resolver;
    }

    protected function _getMongoService(): MongoService
    {
        return $this->getResolver()->getApp()->getMongoService();
    }

    public function getStartedAt(): ?DateTime
    {
        return $this->startedAt;
    }

    public function getEndedAt(): ?DateTime
    {
        return $this->endedAt;
    }

    public function getMeta(): ParameterBag
    {
        return $this->meta;
    }
}