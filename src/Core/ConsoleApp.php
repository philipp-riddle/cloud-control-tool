<?php

namespace Phiil\CloudTools\Core;

use Phiil\CloudTools\Core\Command\CommandResolver;
use Phiil\CloudTools\Crawler\FileCrawler;

/**
 * This child of App gets used for any CLI action which handles argument parsing etc
 */
class ConsoleApp extends App
{
    private $crawler;
    private $resolver;

    public function __construct()
    {
        parent::__construct();

        $this->crawler = new FileCrawler($this); 
        $this->resolver = new CommandResolver($this);
        $this->resolver->load();
    }

    public function getResolver(): CommandResolver
    {
        return $this->resolver;
    }

    public function getCrawler(): FileCrawler
    {
        return $this->crawler;
    }
}