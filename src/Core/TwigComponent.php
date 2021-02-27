<?php

namespace Phiil\CloudTools\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigComponent
{
    private $loader;
    private $environment;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(dirname(__FILE__).'/../../templates');
        $this->environment = new Environment($this->loader, ['debug' => true]);
    }

    public function getTwigEnvironment(): Environment
    {
        return $this->environment;
    }

    public function getTwigLoader(): FilesystemLoader
    {
        return $this->loader;
    }
}