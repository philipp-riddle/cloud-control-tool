<?php

namespace Phiil\CloudTools\Database\Repository;

use Phiil\CloudTools\Database\Entity\DirectoryMeta;
use Phiil\CloudTools\Database\MongoService;

class DirectoryMetaRepository extends Repository
{
    public function __construct(MongoService $mongoService)
    {
        parent::__construct(DirectoryMeta::class, $mongoService);
    }
}