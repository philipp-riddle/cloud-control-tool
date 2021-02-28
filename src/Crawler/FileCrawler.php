<?php

namespace Phiil\CloudTools\Crawler;

use Phiil\CloudTools\Core\ConsoleApp;
use Phiil\CloudTools\Crawler\FileDirectoryCrawler;

class FileCrawler
{
    private $app;

    public function __construct(ConsoleApp $app)
    {
        $this->app = $app;
    }

    public function crawl(string $startDirectoryPath)
    {
        $directoryCrawler = new FileDirectoryCrawler($this);

        return $directoryCrawler->crawlDirectory($startDirectoryPath);
    }

    public function getApp(): ConsoleApp
    {
        return $this->app;
    }
}
