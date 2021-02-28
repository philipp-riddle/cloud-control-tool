<?php

namespace Phiil\CloudTools\Core\Command;

class CommandStyle
{
    public function writeLine(string $line)
    {
        echo $line.PHP_EOL;
    }

    public function writeEmptyLine(int $linesCount = 2)
    {
        echo \str_repeat(PHP_EOL, $linesCount);
    }
}