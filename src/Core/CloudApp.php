<?php

namespace Phiil\CloudTools\Core;

use Phiil\CloudTools\Crawler\FileCrawler;
use Phiil\CloudTools\Database\MongoService;

class CloudApp
{
    private $mongoService;
    private $crawler;

    public function __construct()
    {
        $this->mongoService = new MongoService();
        $this->crawler = new FileCrawler($this);
    }

    /**
     * @return MongoService an instance of the mongo service for helper methods
     */
    public function getMongoService(): MongoService
    {
        return $this->mongoService;
    }

    public function getCrawler(): FileCrawler
    {
        return $this->crawler;
    }
}