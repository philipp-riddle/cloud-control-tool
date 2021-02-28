<?php

namespace Phiil\CloudTools\Core;

use Phiil\CloudTools\Core\Database\MongoService;

class App
{
    protected $mongoService;

    public function __construct()
    {
        $this->mongoService = new MongoService();
    }

    /**
     * @return MongoService an instance of the mongo service for helper methods
     */
    public function getMongoService(): MongoService
    {
        return $this->mongoService;
    }
}
