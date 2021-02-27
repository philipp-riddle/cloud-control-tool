<?php

namespace Phiil\CloudTools\Database\Entity;

class SimpleFile extends File
{
    // filename, e.g. 'test.png'
    protected $fileName;

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName():string
    {
        return $this->fileName;
    }
}