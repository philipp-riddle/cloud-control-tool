<?php

namespace Phiil\CloudTools\Database\Entity;

use DateTime;

class File extends Entity
{
    // path of the file
    protected $path;

    // directory of this file
    protected $directory;

    // type of this file (mp3, jpg, png, ...) OR 'directory' if this file is a directory
    protected $type;

    // bytes in size
    protected $size;

    // datetime when this file first got indexed
    protected $firstIndexedAt;

    // datetime when this file first last got indexed
    protected $lastIndexedAt;

    protected $indexedCounter = 1;

    // '/' would be depth 1, '/Users/' would be 2, ...
    protected $depth;

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPath():string
    {
        return $this->path;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function getDirectory():string
    {
        return $this->directory;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setFirstIndexedAt(DateTime $firstIndexedAt): self
    {
        $this->firstIndexedAt = $firstIndexedAt;

        return $this;
    }

    public function getFirstIndexedAt(): DateTime
    {
        return $this->firstIndexedAt;
    }

    public function setLastIndexedAt(DateTime $lastIndexedAt): self
    {
        $this->lastIndexedAt = $lastIndexedAt;

        return $this;
    }

    public function getLastIndexedAt(): DateTime
    {
        return $this->lastIndexedAt;
    }

    public function setIndexedCounter(int $indexedCounter): self
    {
        $this->indexedCounter = $indexedCounter;

        return $this;
    }

    public function getIndexedCounter(): int
    {
        return $this->indexedCounter;
    }

    public function incrementIndexedCounter(int $incrementedBy = 1): self
    {
        $this->indexedCounter += $incrementedBy;

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->path;
    }

    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function isDirectory(): bool
    {
        return 'directory' === $this->type;
    }

    public static function calculateDepth(string $directory): int
    {
        return \substr_count(\rtrim($directory, '/'), '/');
    }
}