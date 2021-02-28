<?php

namespace Phiil\CloudTools\Database\Repository;

use Phiil\CloudTools\Core\Database\Repository;
use Phiil\CloudTools\Database\Entity\DirectoryMeta;
use Phiil\CloudTools\Database\Entity\File;
use Phiil\CloudTools\Core\Database\MongoService;

class DirectoryMetaRepository extends Repository
{
    public function __construct(MongoService $mongoService)
    {
        parent::__construct(DirectoryMeta::class, $mongoService);
    }

    public function fetchByDirectory(string $directory): ?File
    {
        $result = $this->mongoService->getFilesCollection()->findOne(['directory' => $directory]);

        return $this->__fact($result);
    }
}
