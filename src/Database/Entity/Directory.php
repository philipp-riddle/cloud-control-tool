<?php

namespace Phiil\CloudTools\Database\Entity;

class Directory extends File
{
    // whether there were any more directories beneath this one
    protected $reachedEnd;

    public function __construct()
    {
        $this->type = 'directory';
    }

    public function setReachedEnd(bool $reachedEnd): self
    {
        $this->reachedEnd = $reachedEnd;

        return $this;
    }

    public function hasReachedEnd(): bool
    {
        return $this->reachedEnd;
    }
}