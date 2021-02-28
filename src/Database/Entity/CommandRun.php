<?php

namespace Phiil\CloudTools\Database\Entity;

use DateTime;
use Phiil\CloudTools\Core\Database\Entity;

class CommandRun extends Entity
{
    // the command name
    protected $command;

    // e.g. 'cron' or 'cli'
    protected $user;

    // any data the command wishes to save in the meta array
    protected $meta;

    // datetime when the execution of the command started
    protected $startedAt;

    // datetime when the execution of the command stopped
    protected $endedAt;

    // whether the command returned 'true' or 'false' on execute
    protected $success;

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setMeta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setStartedAt(DateTime $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getStartedAt(): DateTime
    {
        return $this->startedAt;
    }

    public function setEndedAt(DateTime $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getEndedAt(): DateTime
    {
        return $this->endedAt;
    }

    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function getIdentifier(): string
    {
        return \uniqid();
    }
}
