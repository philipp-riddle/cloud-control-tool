<?php

namespace Phiil\CloudTools\Database\Entity;

use DateTime;

class DirectoryMeta extends Entity
{
    protected $directory;
    protected $meta;
    protected $size;
    protected $indexDate;

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function getDirectory(): string
    {
        return $this->directory;
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

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setIndexDate(DateTime $indexDate): self
    {
        $this->indexDate = $indexDate;

        return $this;
    }

    public function getIndexDate(): DateTime
    {
        return $this->indexDate;
    }

    public function getIdentifier(): string
    {
        return $this->directory;
    }
}