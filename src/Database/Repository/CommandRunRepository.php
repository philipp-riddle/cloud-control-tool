<?php

namespace Phiil\CloudTools\Database\Repository;

use Phiil\CloudTools\Core\Database\MongoService;
use Phiil\CloudTools\Core\Database\Repository;
use Phiil\CloudTools\Database\Entity\CommandRun;

class CommandRunRepository extends Repository
{
    public function __construct(MongoService $mongoService)
    {
        parent::__construct(CommandRun::class, $mongoService);
    }
}