<?php

namespace Phiil\CloudTools\Crawler;

use Phiil\CloudTools\Core\CloudApp;
use Phiil\CloudTools\Crawler\FileDirectoryCrawler;

class FileCrawler
{
    private $app;

    public function __construct(CloudApp $app)
    {
        $this->app = $app;
    }

    public function crawl(string $startDirectoryPath)
    {
        $directoryCrawler = new FileDirectoryCrawler($this->getApp()->getCrawler());

        return $directoryCrawler->crawlDirectory($startDirectoryPath);
    }

    public function getApp(): CloudApp
    {
        return $this->app;
    }
}