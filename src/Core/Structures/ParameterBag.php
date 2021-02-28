<?php

namespace Phiil\CloudTools\Core\Structures;

class ParameterBag
{
    protected $parameters;

    public function __construct()
    {
        $this->parameters = [];
    }

    public function add($value)
    {
        $this->parameters[] = $value;
    }

    public function hasValue(string $value): bool
    {
        return \in_array($value, $this->parameters);
    }

    public function set(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    public function getAll(): array
    {
        return $this->parameters;
    }

    public function size(): int
    {
        return \count($this->parameters);
    }

    public function isEmpty(): bool
    {
        return empty($this->parameters);
    }
}