<?php

namespace Phiil\CloudTools\Database\Repository;

use Phiil\CloudTools\Database\Entity\File;
use Phiil\CloudTools\Database\MongoService;

class FileRepository extends Repository
{
    public function __construct(MongoService $mongoService)
    {
        parent::__construct(File::class, $mongoService);
    }

    public function fetchByPath(string $filePath): ?File
    {
       $result = $this->mongoService->getFilesCollection()->findOne(['path' => $filePath]);

       return $this->__fact($result);
    }

    public function fetchAllByDirectory(string $directory)
    {
        $result = $this->mongoService->getFilesCollection()->aggregate([
            ['$match' =>
                ['path' => ['$regex' => $directory]],
            ],
        ]);

        return $this->__factArray($result);
    }
}